<?php
namespace DejwCake\Media\Controller\Media;

use DejwCake\Media\Helpers\UploadedFile;
use DejwCake\Media\Controller\AppController;
use Cake\Event\Event;
use Cake\Log\Log;
use DejwCake\Media\Service\UploadService;

/**
 * Pages Controller
 *
 * @property \DejwCake\StandardCMS\Model\Table\PagesTable $Pages
 */
class UploaderController extends AppController
{
    /**
     * Before filter callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * Check if the provided user is authorized for the request.
     *
     * @param array|\ArrayAccess|null $user The user to check the authorization of.
     *   If empty the user fetched from storage will be used.
     * @param \Cake\Network\Request|null $request The request to authenticate for.
     *   If empty, the current request will be used.
     * @return bool True if $user is authorized, otherwise false
     */
    public function isAuthorized($user = null) {
        return parent::isAuthorized($user);
    }

    public function upload() {
        $this->RequestHandler->renderAs($this, 'json');
        if ($this->request->data('fileinput')) {
            $files = $this->request->data('fileinput');
//            debug($files);
            foreach ($files as $key => $file) {
                $fileInfo = new UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['size'], $file['error']);
                $uploaderService = new UploadService();
                $response[] = $uploaderService->upload($fileInfo);
            }
        } else {
            $response = '';
        }
        $this->set(compact('response'));
        $this->set('_serialize', ['response']);
    }
}
