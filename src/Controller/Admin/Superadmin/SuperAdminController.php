<?php

namespace App\Controller\Admin\Superadmin;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\Interfaces\UploaderInterface;
use App\Entity\User;
use App\Entity\Video;
use App\Form\VideoType;
use App\Entity\Category;


/**
 * @Route("/admin/su")
 */
class SuperAdminController extends AbstractController
{

  private $doctrine;
  public function __construct(ManagerRegistry $doctrine) {
    $this->doctrine = $doctrine;
  }

    /**
     * @Route("/upload-video-locally", name="upload_video_locally")
     */
    public function uploadVideoLocally(Request $request, UploaderInterface $fileUploader)
    {
      $video = new Video();
      $form = $this->createForm(VideoType::class, $video);
      $form->handleRequest($request);


      if ($form->isSubmitted() && $form->isValid())
      {
        $em = $this->doctrine->getManager();

        $file = $video->getUploadedVideo();
        $fileName = $fileUploader->upload($file);

        $base_path = Video::uploadFolder;
        $video->setPath($base_path.$fileName[0]);
        $video->setTitle($fileName[1]);

        $em->persist($video);
        $em->flush();

        return $this->redirectToRoute('videos');
      }

      return $this->render('admin/upload_video_locally.html.twig',[
        'form' => $form->createView()
      ]);
    }

  /**
   * @Route("/set-video-duration/{video}/{vimeo_id}", name="set_video_duration",
   *   requirements={"vimeo_id"=".+"})
   */
  public function setVideoDuration(Video $videoNew, $vimeo_id)
  {
    if( !is_numeric($vimeo_id))
    {
      $videoNew->setDuration(random_int(10,300));
      $em = $this->doctrine->getManager();
      $em->persist($videoNew);
      $em->flush();
      return $this->redirectToRoute('videos');
    }
  }


  /**
   * @Route("/delete-video/{video}/{path}", name="delete_video", requirements={"path"=".+"})
   */
  public function deleteVideo(Video $video, $path, UploaderInterface $fileUploader)
  {

    $em = $this->doctrine->getManager();
    $em->remove($video);
    $em->flush();

    if( $fileUploader->delete($path) )
    {
      $this->addFlash(
        'success',
        'The video was successfully deleted.'
      );
    }
    else
    {
      $this->addFlash(
        'danger',
        'We were not able to delete. Check the video.'
      );
    }

    return $this->redirectToRoute('videos');

  }

    /**
     * @Route("/update-video-category/{video}", methods={"POST"}, name="update_video_category")
     */
    public function updateVideoCategory(Request $request, Video $video)
    {

      $em = $this->doctrine->getManager();

      $category = $this->doctrine->getRepository(Category::class)->find($request->request->get('video_category'));

      $video->setCategory($category);

      $em->persist($video);
      $em->flush();

      return $this->redirectToRoute('videos');
    }

    /**
     * @Route("/users", name="users")
     */
    public function users()
    {

      $repository = $this->doctrine->getRepository(User::class);
      $users = $repository->findBy([], ['name' => 'ASC']);
      return $this->render('admin/users.html.twig',['users'=>$users]);
    }


    /**
     * @Route("/delete-user/{user}", name="delete_user")
     */
    public function deleteUser(User $user)
    {
      $manager = $this->doctrine->getManager();
      $manager->remove($user);
      $manager->flush();

      return $this->redirectToRoute('users');
    }
    

}
