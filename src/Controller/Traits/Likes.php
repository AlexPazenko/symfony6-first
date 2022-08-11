<?php
/*
|--------------------------------------------------------
| copyright netprogs.pl | available only at Udemy.com | further distribution is prohibited  ***
|--------------------------------------------------------
*/
namespace App\Controller\Traits;

use App\Entity\User;

trait Likes {

    private function likeVideo($video, $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $user->addLikedVideo($video);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();    
        return 'liked';
    }

    private function dislikeVideo($video, $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $user->addDislikedVideo($video);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();    
        return 'disliked';
    }


    private function undoLikeVideo($video, $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $user->removeLikedVideo($video);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();    
        return 'undo liked';
    }

    
    private function undoDislikeVideo($video, $doctrine)
    {
        $user = $doctrine->getRepository(User::class)->find($this->getUser());
        $user->removeDislikedVideo($video);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();    
        return 'undo disliked';
    }

}
