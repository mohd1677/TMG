<?php

namespace TMG\Api\LegacyBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use TMG\Api\LegacyBundle\Entity\AxCustomerAddress;

class AxContractRepository extends EntityRepository
{
    private $socialProductCodes = array(
        'MYSOCIAL',
        'MYSOCIALPRO',
        '6MYSOCIAL',
        '6MYSOCIALPRO',
    );

    private $eightHundredProductCodes = array(
        'HTCIT800',
        'TMG800',
        'HTCIT800R',
        'TMG800R',
        '6TMG800',
        '6TMG800R',
        '6HTCIT800',
        '6HTCIT800R',
    );

    private $videoCodes = array('HTCVIDM', '6HTCVIDM', 'HTCPHOTO');
    private $onlineListCodes = array('6HTCIT', 'HTCIT', 'HTCFPL', '6HTCFPL');
    private $featuredListCodes = array('HTCFPL', '6HTCFPL');
    private $reputationProductCodes = array('WDPRSA', '6WDPRSA', 'WDPRBT', '6WDPRBT', 'WDPRRM', '6WDPRRM');
    private $resolveProductCodes = ['TMGRR', 'TMGRR1', 'TMGRR2', 'TMGRRB15', 'TMGRRB30'];
    private $listingManagementCodes = ['TMGLSTM', '6TMGLSTM'];

    private $findBy = array(
        'cu.customerNumber',
        'cu.legacyCustomerNumber',
        'p.name',
        'p.id'
    );

    private $inviableVeStatus = array('4', '5');

    /**
     * Get all social contracts
     *
     * @param string     $search
     * @param string|int $show
     * @param int        $offset
     *
     * @return array
     */
    public function getSocialContracts($search = '', $show = 'all', $offset = 0)
    {
        $query = $this->createQueryBuilder('c')
            ->select('cu.customerNumber as ax, cu.legacyCustomerNumber as e1, p.name, p.id')
            ->join('c.orderNumber', 'o')
            ->join('o.customer', 'cu')
            ->join('TMGApiLegacyBundle:Property', 'p', 'WITH', 'p.axAccountNumber = cu.customerNumber');

        if (!empty($search)) {
            foreach ($this->findBy as $field) {
                $query->orWhere("$field LIKE :search")
                    ->setParameter('search', '%'.$search.'%');
            }
        }

        $query->andWhere('c.itemCode IN (:codes)')
            ->andWhere('c.isActive = :active')
            ->andWhere('cu.customerNumber = p.axAccountNumber')
            ->andWhere('c.startDate < c.endDate')
            ->setParameter('codes', $this->socialProductCodes)
            ->setParameter('active', true);

        if ($show != 'all') {
            $query->setFirstResult($offset)
                ->setMaxResults($show);
        }

        return $query->groupBy('cu.customerNumber')->getQuery()->getResult();
    }

    /**
     * Get total social contracts count
     *
     * @param string $search
     *
     * @return string
     */
    public function getSocialContractsCount($search = '')
    {
        $query = $this->createQueryBuilder('c')
            ->select('COUNT(DISTINCT cu.customerNumber)')
            ->join('c.orderNumber', 'o')
            ->join('o.customer', 'cu')
            ->join('TMGApiLegacyBundle:Property', 'p', 'WITH', 'p.axAccountNumber = cu.customerNumber');

        if (!empty($search)) {
            foreach ($this->findBy as $field) {
                $query->orWhere("$field LIKE :search")
                    ->setParameter('search', '%'.$search.'%');
            }
        }

        return $query->andWhere('c.itemCode IN (:codes)')
            ->andWhere('c.isActive = :active')
            ->andWhere('c.startDate < c.endDate')
            ->setParameter('codes', $this->socialProductCodes)
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get shared ad contracts
     *
     * @param int $id
     * @param string $masterOrderNumber
     * @param string $issue
     *
     * @return array
     */
    public function getSharContracts($id, $masterOrderNumber, $issue)
    {
        return $this->createQueryBuilder('c')
            ->where('c.masterOrderNumber = :masterOrderNumber')
            ->andWhere('c.itemCode LIKE :itemCode')
            ->andWhere('c.id != :id')
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.endIssue >= :issue')
            ->setParameter('masterOrderNumber', $masterOrderNumber)
            ->setParameter('itemCode', '%SHAR')
            ->setParameter('id', $id)
            ->setParameter('issue', $issue)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get 800 contracts
     *
     * @param string $issue
     * @param string $orderNumber
     *
     * @return array
     */
    public function get800Contracts($orderNumber, $issue)
    {
        return $this->createQueryBuilder('c')
            ->select('c.itemCode, c.eightHundredNumber')
            ->where('c.orderNumber = :orderNumber')
            ->andWhere('c.itemCode IN (:codes)')
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.endIssue >= :issue')
            ->setParameter('orderNumber', $orderNumber)
            ->setParameter('codes', $this->eightHundredProductCodes)
            ->setParameter('issue', $issue)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get active 800 contracts
     *
     * @param int|null $max
     * @param int      $min
     *
     * @return array
     */
    public function getTollFreeContracts($max = null, $min = 0)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->getActiveContracts($this->eightHundredProductCodes, true, true)
            ->select('c.axAccountNumber');

        if ($max) {
            $qb->setFirstResult($min)
                ->setMaxResults($max);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get count of active 800 contracts
     *
     * @return int
     */
    public function getTollFreeContractsCount()
    {
        return $this->getActiveContracts($this->eightHundredProductCodes, true, true)
            ->select('Count(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count production Layout Online List
     *
     * @param string $issue
     *
     * @return int
     */
    public function productionLayoutOnlineListCount($issue)
    {
        $now = date("ym");

        $qb = $this->createQueryBuilder('c')
            ->select('Count(c.itemCode)')
            ->where('c.department = :book')
            ->andWhere("c.category != :category")
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.startDate < c.endDate')
            ->setParameter('book', '000')
            ->setParameter('category', 'print');

        if ($issue > $now) {
            $qb
                ->andWhere(
                    'c.endIssue >= :issue
                    or (c.autoRenewOption != :option and c.endIssue >= :now and c.veStatus NOT IN (:veStatus))'
                )
                ->setParameter('option', 0)
                ->setParameter('now', $now)
                ->setParameter('veStatus', $this->inviableVeStatus);
        } else {
            $qb->andWhere('c.endIssue >= :issue');
        }
        $qb->setParameter('issue', $issue);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * List of online production layout
     *
     * @param string $issue
     * @param int    $max
     * @param int    $min
     *
     * @return array
     */
    public function productionLayoutOnlineList($issue, $max = 100, $min = 0)
    {
        $now = date("ym");

        $qb = $this->createQueryBuilder('c')
            ->select('
                c.itemCode,
                c.masterOrderNumber,
                o.legacyOrderNumber,
                o.orderNumber,
                cu.legacyCustomerNumber,
                cu.name,
                cu.customerNumber,
                ca.city,
                ca.state,
                p.id as hash
            ')
            ->join('c.property', 'p')
            ->join('c.orderNumber', 'o')
            ->join('o.customer', 'cu')
            ->join('cu.address', 'ca')
            ->where('c.department = :book')
            ->andWhere("c.category != :category")
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.startDate < c.endDate')
            ->setParameter('book', '000')
            ->setParameter('category', 'print');

        if ($issue > $now) {
            $qb
                ->andWhere(
                    'c.endIssue >= :issue
                    or (c.autoRenewOption != :option and c.endIssue >= :now and c.veStatus NOT IN (:veStatus))'
                )
                ->setParameter('option', 0)
                ->setParameter('now', $now)
                ->setParameter('veStatus', $this->inviableVeStatus);
        } else {
            $qb->andWhere('c.endIssue >= :issue');
        }
        $qb->setParameter('issue', $issue);

        return $qb->setFirstResult($min)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get a list of states that the $bookCode may have.
     *
     * @param string $bookCode
     *
     * @return array
     */
    public function productionLayoutPrintListStates($bookCode)
    {
        $states = $this->createQueryBuilder('c')
            ->select('DISTINCT ca.state as code')
            ->join('c.orderNumber', 'o')
            ->join('o.customer', 'cu')
            ->join('cu.address', 'ca')
            ->where('c.department = :book')
            ->andWhere("c.category = :category")
            ->andWhere('c.startDate < c.endDate')
            ->orderBy('ca.state')
            ->setParameter('book', $bookCode)
            ->setParameter('category', 'print')
            ->getQuery()
            ->getScalarResult();

        foreach ($states as &$state) {
            $state['name'] = AxCustomerAddress::stateNameFromCode($state['code']);
        }

        return $states;
    }

    /**
     * List of print production layout
     *
     * @param array $params
     *
     * @return array
     */
    public function productionLayoutPrintList($params)
    {
        $now = date("ym");

        $qb = $this->createQueryBuilder('c')
            ->select('
                c.id,
                c.itemCode,
                c.adSize,
                c.color,
                c.masterOrderNumber,
                o.legacyOrderNumber,
                o.orderNumber,
                cu.legacyCustomerNumber,
                cu.name,
                cu.customerNumber,
                ca.city,
                ca.interstate,
                ca.highwayExit,
                ca.state,
                p.id as hash
            ')
            ->join('c.property', 'p')
            ->join('c.orderNumber', 'o')
            ->join('o.customer', 'cu')
            ->join('cu.address', 'ca')
            ->leftJoin('p.axContracts', 'pc', 'WITH', '
                pc.axAccountNumber = c.axAccountNumber
                AND c.endIssue < pc.endIssue
                AND pc.startIssue <= :issue
                AND pc.category = :category
                AND pc.status = :status
                AND pc.department = :book
            ')
            ->where('pc.endIssue IS NULL')
            ->andWhere('c.department = :book')
            ->andWhere("c.category = :category")
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.startDate < c.endDate')
            ->setParameter('book', $params['book'])
            ->setParameter('category', 'print')
            ->setParameter('status', 'Active');

        if ($params['issue'] > $now) {
            $qb
                ->andWhere(
                    'c.endIssue >= :issue
                    or (c.autoRenewOption != :option and c.endIssue >= :now and c.veStatus NOT IN (:veStatus))'
                )
                ->setParameter('option', 0)
                ->setParameter('now', $now)
                ->setParameter('veStatus', $this->inviableVeStatus);
        } else {
            $qb->andWhere('c.endIssue >= :issue');
        }
        $qb->setParameter('issue', $params['issue']);

        if (!empty($params['states'])) {
            $qb->andWhere('ca.state IN (:states)')
                ->setParameter('states', $params['states']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * List of results for rate change report.
     *
     * @param array $params
     * @param bool|null $confirmed
     *
     * @return array
     */
    public function rateChangeList($params, $confirmed = null)
    {
        $results = [];

        if (empty($params['book']) && !empty($params['customer'])) {
            //list of books the account have.
            $departments = $this->createQueryBuilder('c')
                ->select('DISTINCT c.department')
                ->where('c.axAccountNumber = :axAccountNumber')
                ->andWhere('c.startIssue <= :issue')
                ->andWhere('c.startDate < c.endDate')
                ->andWhere(
                    'c.endIssue >= :issue
                    or (c.autoRenewOption != :option and c.endIssue >= :now and c.veStatus NOT IN (:veStatus))'
                )
                ->setParameter('issue', $params['issue'])
                ->setParameter('axAccountNumber', $params['customer'])
                ->setParameter('option', 0)
                ->setParameter('now', date('ym'))
                ->setParameter('veStatus', $this->inviableVeStatus)
                ->getQuery()
                ->getScalarResult();

            foreach ($departments as $department) {
                if ($department['department'] == '000') {
                    continue;
                }

                $params['book'] = $department['department'];
                $results = array_merge($results, $this->rateChangeByBookList($params, $confirmed));
            }
        } elseif (!empty($params['book'])) {
            $results = $this->rateChangeByBookList($params, $confirmed);
        }

        return $results;
    }

    /**
     * List of results for rate change report by book code.
     *
     * @param array $params
     * @param bool $confirmed
     *
     * @return array
     */
    public function rateChangeByBookList($params, $confirmed = null)
    {
        $now = date("ym");

        $qb = $this->createQueryBuilder('c')
            ->select('
                c.id,
                c.adSize,
                c.masterOrderNumber,
                c.faxCopy,
                c.emailCopy,
                c.itemType,
                c.itemCode,
                c.department,
                c.eightHundredNumber,
                o.id as orderId,
                o.legacyOrderNumber,
                o.orderNumber,
                cu.legacyCustomerNumber,
                cu.name,
                cu.contactName,
                cu.customerNumber,
                cu.email,
                cu.fax,
                ca.line1,
                ca.city,
                ca.state,
                ca.postalCode,
                b.name as bookName,
                s.name as rep,
                c.startIssue,
                c.endIssue,
                c.autoRenewOption
            ')
            ->join('c.orderNumber', 'o')
            ->join('o.customer', 'cu')
            ->join('cu.address', 'ca')
            ->join('o.salesRep', 's')
            ->join('c.book', 'b')
            ->join('c.property', 'p')
            ->leftJoin('p.axContracts', 'pc', 'WITH', '
                pc.axAccountNumber = c.axAccountNumber
                AND c.endIssue < pc.endIssue
                AND pc.startIssue <= :issue
                AND pc.category IN (:categories)
                AND pc.status = :status
                AND pc.department = :book
            ')
            ->where('pc.endIssue IS NULL')
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.startDate < c.endDate')
            ->andWhere('c.department = :book')
            ->andWhere('c.category IN (:categories)')
            ->andWhere(
                'c.endIssue >= :issue
                or (c.autoRenewOption != :option and c.endIssue >= :now and c.veStatus NOT IN (:veStatus))'
            )
            ->setParameter('issue', $params['issue'])
            ->setParameter('status', 'Active')
            ->setParameter('book', $params['book'])
            ->setParameter('option', 0)
            ->setParameter('now', $now)
            ->setParameter('veStatus', $this->inviableVeStatus);

        if ($confirmed == 'yes') {
            $qb->addSelect('con.id as confId')
                ->join('c.confirmations', 'con')
                ->andWhere('con.confirmed = :issue');
        }

        if (!empty($params['customer'])) {
            $qb->andWhere('cu.legacyCustomerNumber = :customer or cu.customerNumber = :customer')
                ->setParameter('customer', $params['customer']);
        }

        if ($params['ad_type'] == 'print') {
            $qb->setParameter('categories', ['print']);
        } elseif ($params['ad_type'] == '800') {
            $qb->setParameter('categories', ['print', '800']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Used for premium position report, list of all that has a specific position.
     *
     * @param string $book
     * @param string $start
     * @param string $end
     * @param string $position
     *
     * @return array
     */
    public function premiumPositionList($book, $start, $end, $position = null)
    {
        $qb = $this->createQueryBuilder('c')
            ->select(
                'p.id',
                'p.name',
                'p.axAccountNumber as axNumber',
                'p.accountNumber as propertyNumber',
                'o.orderNumber',
                'c.startIssue',
                'c.endIssue',
                'c.position',
                'c.masterOrderNumber',
                'c.itemCode as code',
                'c.autoRenewOption',
                'c.status',
                'c.veStatus',
                'r.name as rep'
            )
            ->join('c.property', 'p')
            ->join('c.orderNumber', 'o')
            ->join('o.salesRep', 'r')
            ->where('c.department = :book')
            ->andWhere('c.category = :type')
            ->andWhere('c.startDate < c.endDate')
            ->andWhere('c.startIssue <= :end')
            ->andWhere(
                '(c.endIssue >= :start)
                or (c.autoRenewOption != :option and c.endIssue < :start and c.veStatus NOT IN (:veStatus))'
            )
            ->orderBy('c.position', 'ASC')
            ->setParameter('option', 0)
            ->setParameter('veStatus', $this->inviableVeStatus)
            ->setParameter('start', $start)
            ->setParameter('book', $book)
            ->setParameter('type', 'print')
            ->setParameter('end', $end);

        if (!empty($params['position'])) {
            $qb = $qb->andWhere('c.position = :position')
                ->setParameter('position', $position);
        } else {
            $qb = $qb->andWhere('c.position is not null');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Get 800 contract product codes
     *
     * @param string $issue
     * @param string $propertyId
     *
     * @return array
     */
    public function get800ContractProductCodes($propertyId, $issue)
    {
        return $this->createQueryBuilder('c')
            ->select('c.itemCode as code')
            ->where('c.property = :propertyId')
            ->andWhere('c.itemCode IN (:codes)')
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.endIssue >= :issue')
            ->setParameters([
                'propertyId' => $propertyId,
                'codes' => $this->eightHundredProductCodes,
                'issue' => $issue
            ])
            ->getQuery()
            ->getResult();
    }

    /**
     * Used for ad change report, get current ad data
     *
     * @param string $book
     * @param string $type
     * @param string $issue
     *
     * @return array
     */
    public function currentAds($book, $type, $issue)
    {
        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'c.masterOrderNumber',
                'c.startIssue',
                'c.endIssue',
                'c.position',
                'c.adSize',
                'c.color',
                'c.itemCode as code',
                'c.description',
                'o.orderNumber',
                'p.id as property',
                'p.axAccountNumber as axNumber',
                'p.accountNumber as propertyNumber',
                'p.name',
                'p.phone',
                'r.name as rep',
                'a.line1',
                'a.city',
                'a.zip as postal',
                'a.state'
            )
            ->join('c.property', 'p')
            ->join('c.orderNumber', 'o')
            ->join('o.salesRep', 'r')
            ->join('p.address', 'a')
            ->where('c.department = :book')
            ->andWhere('c.category = :type')
            ->andWhere('c.startIssue <= :issue')
            ->andWhere('c.endIssue >= :issue')
            ->setParameters(['book' => $book, 'type' => $type, 'issue' => $issue]);

        return $qb->getQuery()->getResult();
    }

    /**
     * Used for ad change report, get previous ad data
     *
     * @param string $book
     * @param string $type
     * @param string $propHash
     * @param string $issue
     *
     * @return array
     */
    public function previousAd($book, $type, $propHash, $issue)
    {
        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'o.orderNumber',
                'c.masterOrderNumber',
                'c.position',
                'c.adSize',
                'c.color',
                'c.startIssue',
                'c.endIssue',
                'c.itemCode as code',
                'c.description'
            )
            ->join('c.orderNumber', 'o')
            ->where('c.department = :book')
            ->andWhere("c.category = :type")
            ->andWhere('c.endIssue < :issue')
            ->andWhere('c.property = :prop')
            ->andWhere('c.startDate < c.endDate')
            ->setParameters([
                'book' => $book,
                'type' => $type,
                'issue' => $issue,
                'prop' => $propHash
            ])
            ->orderBy('c.endIssue', 'DESC');

        return $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
    }

    /**
     * Used for ad change report, get list of previous ads that is missed from current ads list
     *
     * @param string $book
     * @param string $type
     * @param string $issue
     * @param array  $ids
     *
     * @return array
     */
    public function droppedAds($book, $type, $issue, $ids)
    {
        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'o.orderNumber',
                'c.masterOrderNumber',
                'c.startIssue',
                'c.endIssue',
                'c.position',
                'c.adSize',
                'c.color',
                'c.itemCode as code',
                'c.description',
                'p.axAccountNumber as axNumber',
                'p.accountNumber as propertyNumber',
                'p.name',
                'p.phone',
                'p.id as property',
                'r.name as rep',
                'a.line1',
                'a.city',
                'a.zip as postal',
                'a.state'
            )
            ->join('c.property', 'p')
            ->join('c.orderNumber', 'o')
            ->join('o.salesRep', 'r')
            ->join('p.address', 'a')
            ->where('c.department = :book')
            ->andWhere('c.category = :type')
            ->andWhere('c.endIssue = :issue')
            ->andWhere('p.id NOT IN (:ids)')
            ->andWhere('c.startDate < c.endDate')
            ->setParameters(['book' => $book, 'type' => $type, 'issue' => $issue, 'ids' => $ids]);

        return $qb->getQuery()->getResult();
    }

    /**
     * Used for ad change report, count contracts after $issue
     *
     * @param string $book
     * @param string $type
     * @param string $propHash
     * @param string $issue
     *
     * @return bool
     */
    public function hasFutureAd($book, $type, $propHash, $issue)
    {
        $now = date("ym");
        $qb = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.department = :book')
            ->andWhere('c.category = :type')
            ->andWhere('c.property = :prop')
            ->andWhere('c.startDate < c.endDate')
            ->setParameter('book', $book)
            ->setParameter('type', $type)
            ->setParameter('prop', $propHash);

        // if query for future issue
        if ($issue > $now) {
            $qb
                ->andWhere(
                    'c.endIssue >= :issue
                    or (c.autoRenewOption != :option and c.endIssue >= :now and c.veStatus NOT IN (:veStatus))'
                )
                ->setParameter('option', 0)
                ->setParameter('now', $now)
                ->setParameter('veStatus', $this->inviableVeStatus);
        } else {
            $qb->andWhere('c.startIssue >= :issue');
        }
        $qb->setParameter('issue', $issue);

        return $qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * Get active contracts for the given item codes.
     *
     * @param array $contractCodes An array of item codes to narrow the results down to.
     * @param bool  $includeForced Whether or not to include forced contracts.
     * @param bool  $getBuilder
     *
     * @return array|QueryBuilder
     */
    public function getActiveContracts($contractCodes = null, $includeForced = false, $getBuilder = false)
    {
        // Create the query builder.
        $queryBuilder = $this
            ->createQueryBuilder('c');

        // If we were told to include forced contracts (overridden) we need to do so.
        // Doctrine cries if this isn't given before any where statements.
        if ($includeForced) {
            $queryBuilder->join('c.property', 'p');
        }

        $queryBuilder
            ->where('c.startDate <= :now')
            ->andWhere('c.endDate > :now')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'c.collectionMessage IS NULL',
                    'c.collectionMessage NOT IN (:inviableCollectionMessages)'
                )
            )
            ->andWhere('c.property IS NOT NULL')
            ->setParameter('inviableCollectionMessages', ['CA'])
            ->setParameter('now', new \DateTime('now'));

        // If we were given an array of contract codes to narrow it down to, let's do that.
        if ($contractCodes) {
            $queryBuilder
                ->andWhere('c.itemCode IN (:contractCodes)')
                ->setParameter('contractCodes', $contractCodes);
        }

        // Remember the forced contracts? We need to insert our or condition here.
        // Doctrine will miss it if we provide it before the where.
        if ($includeForced && $contractCodes) {
            $queryBuilder->orWhere(
                $queryBuilder->expr()->andX(
                    'p.showOnline = 1',
                    'c.itemCode IN (:contractCodes)'
                )
            );
        } elseif ($includeForced) {
            $queryBuilder->orWhere(
                'p.showOnline = 1'
            );
        }

        if ($getBuilder) {
            return $queryBuilder;
        }

        // Return all active contracts for the parameters given.
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Get active properties for the given item codes.
     *
     * @param array $contractCodes
     * @param bool $includeForced
     *
     * @return array|null
     */
    public function getActiveProperties($contractCodes = null, $includeForced = false)
    {
        $queryBuilder = $this->getActiveContracts($contractCodes, $includeForced, true);

        $queryBuilder->groupBy('c.property');

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Get active or future contracts for the given item codes.
     *
     * @param array $contractCodes
     * @param bool $includeForced
     * @param bool $getBuilder
     *
     * @return array|null
     */
    public function getActiveAndFutureContracts($contractCodes = null, $includeForced = false, $getBuilder = false)
    {
        // Create the query builder.
        $queryBuilder = $this
            ->createQueryBuilder('c');

        // If we were told to include forced contracts (overridden) we need to do so.
        // Doctrine cries if this isn't given before any where statements.
        if ($includeForced) {
            $queryBuilder->join('c.property', 'p');
        }

        $queryBuilder
            ->where('c.endDate > :now')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    'c.collectionMessage IS NULL',
                    'c.collectionMessage NOT IN (:inviableCollectionMessages)'
                )
            )
            ->andWhere('c.property IS NOT NULL')
            ->andWhere('c.status = :status')
            ->setParameter('inviableCollectionMessages', ['CA'])
            ->setParameter('now', new \DateTime('now'))
            ->setParameter('status', 'Active');

        // If we were given an array of contract codes to narrow it down to, let's do that.
        if ($contractCodes) {
            $queryBuilder
                ->andWhere('c.itemCode IN (:contractCodes)')
                ->setParameter('contractCodes', $contractCodes);
        }

        // Remember the forced contracts? We need to insert our or condition here.
        // Doctrine will miss it if we provide it before the where.
        if ($includeForced && $contractCodes) {
            $queryBuilder->orWhere(
                $queryBuilder->expr()->andX(
                    'p.showOnline = 1',
                    'c.itemCode IN (:contractCodes)'
                )
            );
        } elseif ($includeForced) {
            $queryBuilder->orWhere(
                'p.showOnline = 1'
            );
        }

        if ($getBuilder) {
            return $queryBuilder;
        }

        // Return all active contracts for the parameters given.
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Video contracts
     *
     * @param int $status
     * @param int $offset
     * @param int|string $max
     *
     * @return QueryBuilder
     */
    public function getVideoContracts($status = null, $offset = 0, $max = 'all')
    {
        $query = $this->createQueryBuilder('c')
            ->select(
                'p.id as propertyId',
                'p.axAccountNumber as ax',
                'p.e1AccountNumber as e1',
                's.name as rep',
                'a.state',
                'p.name',
                'c.startDate',
                'c.endDate',
                'c.itemCode'
            )
            ->join('c.property', 'p')
            ->join('c.orderNumber', 'o')
            ->join('o.salesRep', 's')
            ->join('p.address', 'a')
            ->where('c.endDate > :now')
            ->andWhere('c.itemCode IN (:codes)')
            ->andWhere('c.startDate < c.endDate')
            ->andWhere('c.collectionMessage IS NULL OR c.collectionMessage NOT IN (:inviableCollectionMessages)')
            ->setParameter('now', new \DateTime('now'))
            ->setParameter('codes', $this->videoCodes)
            ->setParameter('inviableCollectionMessages', ['CA']);

        if ($status) {
            if ($status == 1) {
                $query->andWhere('p.video IS NULL');
            } else {
                $query
                    ->addSelect('v.playerId', 'v.vidyardId', 'v.status', 'v.notes')
                    ->join('p.video', 'v')
                    ->andWhere('v.status = :status')
                    ->setParameter('status', $status);
            }
        } else {
            $query
                ->addSelect('v.playerId', 'v.vidyardId', 'v.status', 'v.notes')
                ->join('p.video', 'v');
        }

        if ($max != 'all') {
            $query
                ->setFirstResult($offset)
                ->setMaxResults($max);
        }

        $query->orderBy('c.endDate', 'ASC');

        return $query;
    }

    /**
     * Is property has a video contract
     *
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasVideoContract($propertyId)
    {
        $qb = $this->getActiveContracts($this->videoCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Is property has a featured contract
     *
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasFeaturedContract($propertyId)
    {
        $qb = $this->getActiveContracts($this->featuredListCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Does property have a social contract
     *
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasSocialContract($propertyId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getActiveContracts($this->socialProductCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Returns true if this propertyId has an active or future Social Contract
     *
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasActiveOrFutureSocialContract($propertyId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getActiveAndFutureContracts($this->socialProductCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;

    }

    /**
     * Does property have a reputation contract
     *
     * @param $propertyId
     *
     * @return bool
     */
    public function hasReputationContract($propertyId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getActiveContracts($this->reputationProductCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Does property have a resolve contract
     *
     * @param $propertyId
     *
     * @return bool
     */
    public function hasActiveResolveContract($propertyId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getActiveContracts($this->resolveProductCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Does property have a current or future reputation contract
     *
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasActiveOrFutureReputationContract($propertyId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getActiveAndFutureContracts($this->reputationProductCodes, false, true);
        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;

    }

    /**
     * Does property have an active Listing Management contract
     *
     * @param string $propertyId
     *
     * @return bool
     */
    public function hasListingManagementContract($propertyId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $qb */
        $qb = $this->getActiveContracts($this->listingManagementCodes, false, true);

        $count = $qb->select('COUNT(c)')
            ->andWhere('c.property = :property')
            ->setParameter('property', $propertyId)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Get active contracts with rate expired.
     *
     * @return array
     */
    public function getExpiredRateList()
    {
        //acitve contracts with rate
        $ids = $this->getActiveContracts($this->onlineListCodes, true, true)
            ->select('DISTINCT p.id')
            ->join('p.advertisements', 'a')
            ->andWhere('a.startDate <= :now')
            ->andWhere('a.endDate > :now')
            ->getQuery()
            ->getScalarResult();

        $idsWithRate = array_map('current', $ids);

        //acitve contracts without rate
        $results = $this->getActiveContracts($this->onlineListCodes, true, true)
            ->andWhere('p.id NOT IN (:ids)')
            ->setParameter('ids', $idsWithRate)
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();

        return $results;
    }

    /**
     * Get contracts that will active in a month without rate setup.
     *
     * @return array
     */
    public function getNoFutureRateList()
    {
        $now = new \DateTime('now');
        $oneMonth = new \DateTime('1 month');

        //contracts starting in one month has no rate setup
        $results = $this->createQueryBuilder('c')
            ->select('p.id as propertyId, p.axAccountNumber, p.name, addr.line1, addr.city, addr.state, c.startDate')
            ->join('c.property', 'p')
            ->join('p.address', 'addr')
            ->leftJoin('p.advertisements', 'a', 'WITH', 'a.endDate > :now')
            ->where('c.itemCode IN (:codes)')
            ->andWhere('c.startDate > :now')
            ->andWhere('c.startDate <= :oneMonth')
            ->andWhere('c.startDate < c.endDate')
            ->andWhere('a.startDate is NULL')
            ->setParameters(
                [
                    'codes' => $this->onlineListCodes,
                    'now' => $now,
                    'oneMonth' => $oneMonth
                ]
            )
            ->groupBy('p.id')
            ->orderBy('c.startDate', 'ASC')
            ->getQuery()
            ->getResult();

        return $results;
    }

    /**
     * Get active contracts with rate expiring in 15 days
     *
     * @return array
     */
    public function getExpiringRateList()
    {
        $fifteenDay = new \DateTime('15 days');

        //active contracts with rate or future rate and not expiring in 15 days
        $futureAds = $this->getActiveContracts($this->onlineListCodes, true, true)
            ->select('DISTINCT p.id')
            ->join('p.advertisements', 'a')
            ->andWhere('a.endDate > :fifteenDay')
            ->setParameter('fifteenDay', $fifteenDay)
            ->getQuery()
            ->getScalarResult();

        $ids = array_map('current', $futureAds);

        //active ads that will be expired in 15 days.
        $results = $this->getActiveContracts($this->onlineListCodes, true, true)
            ->select(
                'c.id,
                p.id as propertyId,
                p.axAccountNumber,
                p.name,
                addr.line1,
                addr.city,
                addr.state,
                a.ratePretty,
                a.endDate'
            )
            ->join('p.advertisements', 'a')
            ->join('p.address', 'addr')
            ->andWhere('a.endDate > :now')
            ->andWhere('a.endDate <= :fifteenDay')
            ->andWhere('c.endDate > :fifteenDay')
            ->andWhere('p.id NOT IN (:ids)')
            ->setParameter('fifteenDay', $fifteenDay)
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult();

        return $results;
    }

    /**
     * Videos without DESCRIPTION or TITLE
     *
     * @return array
     */
    public function getMissingVideoDescriptionList()
    {
        $results = $this->getVideoContracts()
            ->andWhere('v.description IS NULL OR v.description = :desc OR v.title IS NULL OR v.title = :title')
            ->setParameter('desc', 'NEEDS DESCRIPTION')
            ->setParameter('title', 'NEEDS TITLE')
            ->getQuery()
            ->getResult();

        return $results;
    }
}
