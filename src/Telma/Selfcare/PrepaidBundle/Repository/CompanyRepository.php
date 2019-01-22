<?php

namespace Telma\Selfcare\PrepaidBundle\Repository;

class CompanyRepository extends \Doctrine\ORM\EntityRepository
{
    public function filter($companyName, $status, $debutDate, $endDate)
    {
        //formatter date debut
        $dd = substr($debutDate, 0, 2);
        $md = substr($debutDate, 3, 2);
        $yd = substr($debutDate, 6, 4);
        $debutDate = $yd . "-" . $md . "-" . $dd;
        // formatter date end
        $de = substr($endDate, 0, 2);
        $me = substr($endDate, 3, 2);
        $ye = substr($endDate, 6, 4);
        $endDate = $ye . "-" . $me . "-" . $de;

        $queryBuilder = $this->createQueryBuilder('CO');
        $queryBuilder->add('select', 'CO');
        $queryBuilder->add('from', 'TelmaSelfcarePrepaidBundle:Company CO');
        $queryBuilder->where(' 1=1 ');

        if($companyName != '') {
            $queryBuilder->andWhere(' CO.companyName LIKE :companyName ');
            $queryBuilder->setParameter('companyName', '%'.$companyName.'%');
        }
        if($status != '') {
            $queryBuilder->andWhere(' CO.status = :status ');
            $queryBuilder->setParameter('status', $status);
        }

        if ($debutDate != '' && $endDate != '') {
            $queryBuilder->andWhere(' ( CO.creationDate BETWEEN :debutDate AND :endDate ) ');
            $queryBuilder->setParameter('debutDate', $debutDate);
            $queryBuilder->setParameter('endDate', $endDate);
        }
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        return $result;
    }

}