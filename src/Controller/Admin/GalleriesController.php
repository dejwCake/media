<?php
namespace DejwCake\Media\Controller\Admin;

use DejwCake\Media\Controller\Admin\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ConflictException;
use Cake\Routing\Router;
use Cake\Event\Event;
use Cake\Log\Log;

/**
 * Galleries Controller
 *
 * @property \DejwCake\Media\Model\Table\GalleriesTable $Galleries
 */
class GalleriesController extends AppController
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
        return parent::isAuthorized($user);;
    }

    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $galleries = $this->Galleries->find('translations', [
            'order' => [
                'Galleries.sort' => 'asc'
            ]
        ]);

        $this->set(compact('galleries'));
        $this->set('_serialize', ['galleries']);
    }

    /**
     * View method
     *
     * @param string|null $id Gallery id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gallery = $this->Galleries->find('translations', [
            'contain' => ['Media' => function ($query) {
                return $query->find('translations');
            }]
        ])->where(['Galleries.id' => $id])->firstOrFail();

        $collections = $this->Galleries->getMediaCollections();
        $this->set(compact('gallery', 'collections'));
        $this->set('_serialize', ['gallery']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gallery = $this->Galleries->newEntity();
        if ($this->request->is('post')) {
            $gallery = $this->Galleries->patchEntity($gallery, $this->request->data, [
                'translations' => true
            ]);
            if ($this->Galleries->save($gallery)) {
                $this->Flash->success(__d('dejw_cake_media', 'The gallery has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                Log::error('Entity could not be saved. Entity: '.var_export($gallery, true));
                $this->Flash->error(__d('dejw_cake_media', 'The gallery could not be saved. Please, try again.'));
            }
        }
        $enabledInLocales = $this->getLocales();
        $collections = $this->Galleries->getMediaCollections();
        $this->set(compact('gallery', 'enabledInLocales', 'collections'));
        $this->set('_serialize', ['gallery']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Gallery id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gallery = $this->Galleries->find('translations', [
            'contain' => ['Media' => function ($query) {
                return $query->find('translations');
            }]
        ])->where(['Galleries.id' => $id])->firstOrFail();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gallery = $this->Galleries->patchEntity($gallery, $this->request->data, [
                'translations' => true
            ]);
            if ($this->Galleries->save($gallery)) {
                $this->Flash->success(__d('dejw_cake_media', 'The gallery has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                Log::error('Entity could not be saved. Entity: '.var_export($gallery, true));
                $this->Flash->error(__d('dejw_cake_media', 'The gallery could not be saved. Please, try again.'));
            }
        }
        $enabledInLocales = $this->getLocales();
        $collections = $this->Galleries->getMediaCollections();
        $this->set(compact('gallery', 'enabledInLocales', 'collections'));
        $this->set('_serialize', ['gallery']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Gallery id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $gallery = $this->Galleries->get($id);
        if ($this->Galleries->delete($gallery)) {
            $this->Flash->success(__d('dejw_cake_media', 'The gallery has been deleted.'));
        } else {
            $this->Flash->error(__d('dejw_cake_media', 'The gallery could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Sort method
     *
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function sort()
    {
        $galleries = $this->Galleries->find('all', [
            'fields' => ['id', 'title'],
            'order' => ['sort' => 'ASC'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $items = json_decode($this->request->data('ids'));
            if (!is_array($items)) {
                throw new BadRequestException(__d('dejw_cake_media', 'You must pass an array to sort.'));
            }
            $this->Galleries->setNewSort($items);
            $this->Flash->success(__d('dejw_cake_media', 'The gallery order has been changed.'));
        }

        $this->set(compact('galleries'));
        $this->set('_serialize', ['galleries']);
    }
}
