<?php
class SeoUrisController extends SeoAppController {

	var $name = 'SeoUris';
	var $helpers = array('Time');
	var $uses = array('Seo.SeoUri');
	
	private function clearAssociatesIfEmpty(){
		foreach($this->data['SeoMetaTag'] as $key => $metatag){
			if(isset($metatag['name']) && empty($metatag['name'])){
				unset($this->data['SeoMetaTag'][$key]);
			}
		}
		if(empty($this->data['SeoMetaTag'])){
			unset($this->data['SeoMetaTag']);
		}
		if(isset($this->data['SeoTitle']['title']) && empty($this->data['SeoTitle']['title'])){
			unset($this->data['SeoTitle']);
		}
	}
	
	function admin_index($filter = null) {
		if(!empty($this->data)){
			$filter = $this->data['SeoUri']['filter'];
		}
		$conditions = $this->SeoUri->generateFilterConditions($filter);
		$this->set('seoUris',$this->paginate($conditions));
		$this->set('filter', $filter);
	}
	
	function admin_urlencode($id = null){
		if($this->SeoUri->urlEncode($id)){
			$this->Session->setFlash("uri Successfully Url Encoded.");
		}
		else {
			$this->Session->setFlash("Erorr URL Encoding uri");
		}
		$this->redirect(array('action' => 'view', $id));
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid seo uri'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('seoUri', $this->SeoUri->findForViewById($id));
		$this->set('id', $id);
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->SeoUri->clear();
			$this->clearAssociatesIfEmpty();
			if ($this->SeoUri->saveAll($this->data)) {
				$this->Session->setFlash(__('The seo uri has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The seo uri could not be saved. Please, try again.'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid seo uri'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->clearAssociatesIfEmpty();
			if ($this->SeoUri->save($this->data)) {
				$this->Session->setFlash(__('The seo uri has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The seo uri could not be saved. Please, try again.'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->SeoUri->findForViewById($id);
		}
		$this->set('status_codes', $this->SeoUri->SeoStatusCode->findCodeList());
		$this->set('id', $id);
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for seo uri'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->SeoUri->delete($id)) {
			$this->Session->setFlash(__('Seo uri deleted'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Seo uri was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	function admin_approve($id = null){
	  if(!$id) {
			$this->Session->setFlash(__('Invalid id for seo uri'));
		}
		elseif($this->SeoUri->setApproved($id)) {
			$this->Session->setFlash(__('Seo Uri approved'));
		}
		$this->redirect(array('admin' => true, 'action' => 'index'));
	}
}
?>