<?php

namespace App\Presenters;

use App\Model\Entity\Post;
use App\Model\Facade\PostFacade;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Form;


class HomepagePresenter extends BasePresenter
{
    /**
     * @inject
     * @var EntityManager
     */
    public $entityManager;

    /**
     * @inject
     * @var PostFacade
     */
    public $postFacade;

    /**
     * @var integer
     */
    private $replyId;

    /**
     * @var Post
     */
    private $replyPostInfo;

    public function actionDefault($id = 0)
    {
        if (intval($id) > 0) {
            $this->replyId = intval($id);

            $replyPostEntity = $this->postFacade->getPostInfo($this->replyId);

            if (!is_null($replyPostEntity)) {
                $this->replyPostInfo = $replyPostEntity;

                $this["postForm"]->setDefaults([
                    'replyId' => $this->replyId,
                ]);
            } else {
                $this->replyId = -1;
            }
        }
    }

    public function renderDefault()
    {
        $this->template->posts = $this->postFacade->getAllPosts();
        $this->template->replyId = $this->replyId;
        if ($this->replyId > 0) {
            $this->template->replyPostInfo = $this->replyPostInfo;
        }
    }

    /**
     * @return Form
     */
    protected function createComponentPostForm()
    {
        $form = new Form();

        $form->addText('name', 'Jméno:')
            ->addRule(Form::FILLED, 'Zadejte prosím Vaše jméno.');
        $form->addTextArea('post', 'Text příspěvku:', 50, 5)
            ->addRule(Form::FILLED, 'Zadejte prosím text příspěvku.');
        $form->addHidden('replyId', 0);
        $form->addSubmit('submit', 'Vložit');
        $form->onSuccess[] = [$this, 'postFormSubmitted'];

        return $form;
    }

    /**
     * @param Form $form
     * @param $values
     */
    public function postFormSubmitted(Form $form, $values)
    {
        $this->postFacade->createNewPost($values->name, $values->post, $values->replyId);

        $this->flashMessage('Váš vzkaz byl publikován', 'success');
        $this->redirect('Homepage:');
    }
}
