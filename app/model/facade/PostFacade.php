<?php

namespace App\Model\Facade;

use App\Model\Entity\Post;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Michal Oktábec <info@michaloktabec.cz>
 */
class PostFacade
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * PostFacade constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getAllPosts()
    {
        return $this->entityManager->getRepository(Post::class)
            ->findBy([], ['dateTime' => 'desc']);
    }

    /**
     * @return array
     */
    public function getPostInfo($postId)
    {
        return $this->entityManager->getRepository(Post::class)
            ->findOneBy(['id' => $postId]);
    }

    /**
     * @param string $name
     * @param string $post
     */
    public function createNewPost($name, $post, $replyId)
    {
        $newPost = new Post();
        $newPost->setName($name);
        $newPost->setPost($post);

        if (intval($replyId) > 0 && !is_null($this->getPostInfo($replyId))) {
            $newPost->setReplyTo($this->getPostInfo($replyId));
        }

        $this->entityManager->persist($newPost);
        $this->entityManager->flush();
    }

}
