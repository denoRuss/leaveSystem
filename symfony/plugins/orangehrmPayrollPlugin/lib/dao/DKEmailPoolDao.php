<?php


class DKEmailPoolDao
{
    /**
     * save email object in email pool
     *
     * @param $email
     * @return mixed
     * @throws DaoException
     */
    public function saveEmail($email){
        try{
            $email->save();
            return $email;
        }
        catch (Exception $e){
            throw new DaoException($e->getMessage());
        }
    }


    /**
     * search EmailPool by email status
     *
     * @param array $status
     * @return Doctrine_Collection
     * @throws DaoException
     */
    public function searchEmailPoolByStatus($status=array()) {
        try {
            $q = Doctrine_Query::create()
                ->from('EmailPool')
                ->whereIn('status ', $status);

            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }
}