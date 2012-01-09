<?php

namespace Kunstmaan\AdminBundle\Repository;

use Kunstmaan\AdminBundle\Entity\DeepCloneableIFace;

use Kunstmaan\AdminBundle\Entity\DraftConnector;

use Doctrine\ORM\EntityRepository;
use Kunstmaan\AdminBundle\Modules\ClassLookup;

/**
 * BlogRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DraftConnectorRepository extends EntityRepository
{
	public function getDraft($publicEntity){
		$publicId = $publicEntity->getId();
		$entityname = ClassLookup::getClass($publicEntity);
		if(! $publicEntity instanceof DeepCloneableIFace){
			throw new \Exception("the entity of class ". $entityname . " must implement DeepCloneableIFace");
		}
		$draftConnector = $this->findOneBy(array("publicId" => $publicId, "entityname" => $entityname ));
		if(!$draftConnector){
			$draftConnector = new DraftConnector();
			$draftConnector->setPublicId($publicEntity->getId());
			$draftConnector->setEntityname($entityname);
			$draftEntity = $publicEntity->deepClone($this->getEntityManager());
			$draftConnector->setDraftId($draftEntity->getId());
			$this->getEntityManager()->persist($draftConnector);
			$this->getEntityManager()->flush();
			return $draftEntity;
		}
		return $this->getEntityManager()->getRepository($draftConnector->getEntityname())->find($draftConnector->getDraftId());
	}
	
	public function copyDraftToPublishedReturnPublished($draftEntity){
		$em = $this->getEntityManager();
		$entityname = ClassLookup::getClass($draftEntity);
		$draftConnector = $this->findOneBy( array("draftId" => $draftEntity->getId(), "entityname" => $entityname ));
		$oldPublicEntity = $this->getEntityManager()->getRepository($draftConnector->getEntityname())->find($draftConnector->getPublicId());
		$node = $em->getRepository('KunstmaanAdminNodeBundle:Node')->getNodeFor($oldPublicEntity);
		$this->getEntityManager()->remove($oldPublicEntity);
		$newPublicEntity = $draftEntity->deepClone($em);
		$node->setRefId($newPublicEntity->getId());
		$node->setTitle($newPublicEntity->getTitle());
		$em->persist($node);
		$em->flush();
		return $newPublicEntity;
	}
	
	public function saveAsDraftAndReturnPublish($oldPublicEntity){
		$em = $this->getEntityManager();
		$entityname = ClassLookup::getClass($oldPublicEntity);
		$node = $em->getRepository('KunstmaanAdminNodeBundle:Node')->getNodeFor($oldPublicEntity);
		$draftConnector = $this->findOneBy( array("publicId" => $oldPublicEntity->getId(), "entityname" => $entityname ));
		$newpublic = $oldPublicEntity->deepClone($em);
		$em->flush();
		if(!$draftConnector){
			$draftConnector = new DraftConnector();
			$draftConnector->setEntityname($entityname);
		}
		$draftConnector->setPublicId($newpublic->getId());
		$draftConnector->setDraftId($oldPublicEntity->getId());
		$em->persist($draftConnector);
		$em->flush();
		$node->setRefId($newpublic->getId());
		$em->persist($node);
		$em->flush();
		return $newpublic;
	}
	
	public function createNewDraft($publicEntity){
		//remove old
		$publicId = $publicEntity->getId();
		$entityname = ClassLookup::getClass($publicEntity);
		if(! $publicEntity instanceof DeepCloneableIFace){
			throw new \Exception("the entity of class ". $entityname . " must implement DeepCloneableIFace");
		}
		$draftConnector = $this->findOneBy(array("publicId" => $publicId, "entityname" => $entityname ));
		if($draftConnector){
			$this->getEntityManager()->remove($publicEntity);
		} else {
			$draftConnector = new DraftConnector();
			$draftConnector->setPublicId($publicEntity->getId());
			$draftConnector->setEntityname($entityname);
		}
		$draftEntity = $publicEntity->deepClone($this->getEntityManager());
		$draftConnector->setDraftId($draftEntity->getId());
		$this->getEntityManager()->persist($draftConnector);
		$this->getEntityManager()->flush();
		return $draftEntity;
	}
}