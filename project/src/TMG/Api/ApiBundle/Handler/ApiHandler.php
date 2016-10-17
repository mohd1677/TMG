<?php

namespace TMG\Api\ApiBundle\Handler;

use Doctrine\ORM\EntityManager;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TMG\Api\ApiBundle\Exception as Exception;
use TMG\Api\ApiBundle\Exception\General as GeneralException;

abstract class ApiHandler extends ContainerAware
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Object
     */
    protected $class;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /** @var  bool */
    protected $isLegacy;

    /**
     * @param ValidatorInterface $validator
     * @param bool|false $isLegacy
     */
    public function __construct(ValidatorInterface $validator, $isLegacy = false)
    {
        $this->isLegacy = $isLegacy;
        $this->validator = $validator;
    }


    /**
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $this->isLegacy ? $this->container->get('doctrine')->getManager('legacy') : $em;
    }

    /**
     * Sets the entity class this handler handles.
     *
     * @param $class
     */
    public function setClass($class)
    {
        $this->class = $class;
        $this->repository = $this->em->getRepository($class);
    }

    /**
     * Returns repository for handler.
     *
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param array $criteria
     * @return mixed
     */
    public function findOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * @param $resource
     * @return mixed
     */
    public function save($resource)
    {
        // validate
        $errors = $this->validator->validate($resource);

        if (count($errors) > 0) {
            throw new Exception\ValidationException($errors);
        }

        $this->persist($resource);

        return $resource;

    }

    /**
     * Persists a resource to the database
     *
     * @param object $resource The resource to persist
     */
    final protected function persist($resource)
    {
        $this->em->persist($resource);
        $this->flush();
    }

    /**
     * Flush the entityManager
     */
    final public function flush()
    {
        $this->em->flush();
    }

    /**
     * Start a Transaction
     */
    final public function beginTransaction()
    {
        $this->em->getConnection()->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    final public function commitTransaction()
    {
        $this->em->flush();
        $this->em->getConnection()->commit();
    }

    /**
     * Rollback a transaction
     */
    final public function rollbackTransaction()
    {
        $this->em->getConnection()->rollBack();
    }

    /**
     * Retrieves a record from the repository
     *
     * @param integer|array $id The id of the resource to load (integer or an array if compound index)
     * @param bool $notFoundException Should a not found exception be thrown?
     *
     * @return
     *
     * @throws Exception\NotFoundHttpException
     */
    public function get($id, $notFoundException = true)
    {
        $response = $this->getRepository()->find($id);

        if (!$response and $notFoundException) {
            throw new GeneralException\ObjectWithIdNotFoundException(
                [
                    'type' => $this->class,
                    'id' => $id,
                ]
            );
        }

        return $response;
    }

    /**
     * Saves a new resource
     *
     * @param $resource
     *
     * @return mixed
     */
    public function post($resource)
    {
        return $this->save($resource);
    }

    /**
     * Saves an existing resource
     *
     * @param $resource
     *
     * @return mixed
     */
    public function put($resource)
    {
        return $this->save($resource);
    }

    /**
     * Saves an existing resource
     *
     * @param $resource
     *
     * @return mixed
     */
    public function patch($resource)
    {
        return $this->save($resource);
    }


    /**
     * Delete a resource by $id
     *
     * @param int $id The primary key id of the resource to delete.
     */
    public function delete($id)
    {
        $object = $this->get($id);
        $this->remove($object);
    }

    /**
     * Removes a resource from the system
     *
     * @param object $resource The resource to remove
     */
    final public function remove($resource)
    {
        $this->em->remove($resource);
        $this->flush();
    }
}
