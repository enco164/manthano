<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 1/3/14
 * Time: 6:13 PM
 */

class Proposal
{
    private $idProposal;
    private $UserProposed;
    private $Name;
    private $Description;
    private $db;
    private $exist;

    public function __construct($id, $db)
    {
        $this->db = $db;
        $stmt = $db->prepare("SELECT UserProposed, Name, Description FROM Proposal WHERE idProposal = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount())
            $this->exist = 1;
        else
            $this->exist = 0;

        $this->idProposal = $id;
        $this->UserProposed = $result['UserProposed'];
        $this->Name = $result['Name'];
        $this->Description = $result['Description'];
    }

    public function __destruct()
    {
        $this->idProposal = null;
        $this->UserProposed = null;
        $this->Name = null;
        $this->Description = null;
        $this->db = null;
        $this->exist = null;
    }

    public function __toString()
    {
        return $this->UserProposed." ".$this->Name." ".$this->Description;
    }

    /*check existence of proposal*/
    public function exists()
    {
        return $this->exist;
    }

    /*check existence of proposal with that id*/
    static public function idExists($db, $id)
    {
        $stmt = $db->prepare("SELECT Name FROM Proposal WHERE idProposal = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() ? true : false;
    }

    /* getters and setters*/
    public function idProposal(){ return $this->idProposal;}
    public function UserProposed() {return $this->UserProposed; }
    public function Name(){ return $this->Name;}
    public function Description(){ return $this->Description;}

    public function setUserProposed($value){ $this->UserProposed = $value;}
    public function setName($value){ $this->Name = $value;}
    public function setDescription($value){ $this->Description = $value;}

    /* error handling for Accessing the wrong way to private properties of object*/
    public function __set($name, $value)
    {
        echo "You are trying to direct set property ".$name." to value ".$value." and thats not possible. Use set".$name."(\$value) method.";
        echo "<br/> At line ".__LINE__." in file ".__FILE__;
    }
    public function __get($name){
        echo "You are trying to direct access property ".$name." and thats not possible. Use ".$name."() method";
        echo "<br/> At line ".__LINE__." in file ".__FILE__;
    }

    /* get all info about proposal
     * Return value is array idProposal, UserProposed, Name, Description
     */
    public function getFullInfo()
    {
        if($this>exists)
            $result = [ "idProposal" => $this->idProposal, "UserProposed" => $this->UserProposed,
                        "Name" => $this->Name, "Description" => $this->Description];
        else
            $result = "Predlog ne postoji! ";

        return $result;
    }

    /* get all users who add vote for this proposal
     * Return value is array of user id's who add vote for this proposal
     */
    public function getSupport()
    {
        $stmt = $this->db->prepare("SELECT u.user_id, u.Name, u.Surname from ProposalSupport ps join User u on ps.user_id = u.user_id                                   where idProposal = :id order by Name Desc, Surname Desc");
        $stmt->bindParam(":id", $this->idProposal, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() == 0)
            return "Niko ne podržava ovaj predlog!";
        else{
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $ret = array();
            foreach($result as $i){
                array_push($ret, $i);
            }
            return $ret;
        }
    }

    /* get number of users who add vote for this proposal
     * Return value is number of users
     */
    public function getSupportCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ProposalSupport WHERE idProposal = :id");
        $stmt->bindParam(":id", $this->idProposal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /* get potential owners when Proposal->Activty
     * Return value is id's of potential owners
     */
    public function getOwners()
    {
        $stmt = $this->db->prepare("SELECT u.user_id, u.Name, u.Surname, Count(*) as 'Count' from ProposalOwner po join User u
                                                    on po.UserProposed = u.user_id
                              where idProposal = :id
                              group by u.user_id, u.Name, u.Surname
                              order by Name ASC, Surname ASC");

        $stmt->bindParam(":id", $this->idProposal, PDO::PARAM_INT);
        $stmt->execute();
        if( $stmt->rowCount()  == 0)
        {
            return "Nema predloga za vlasnika! ";
        }
        else
        {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    /* get number of potential owners when Proposal->Activty
     * Return value is number of  potential owners
     */
    public function getOwnerCount()
    {
        $stmt = $this->db->prepare("SELECT COUNT(DISTINCT UserProposed) FROM ProposalOwner WHERE idProposal = :id");
        $stmt->bindParam(":id", $this->idProposal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }


    /* update proposal in DB with this one
     * return value is true if successfull or false if not.
     */
    public function updateProposal()
    {
        $query = "UPDATE Proposal SET UserProposed = :userId, Name = :name, Description = :desc WHERE idProposal = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":userId", $this->UserProposed, PDO::PARAM_INT);
        $stmt->bindParam(":name", $this->Name, PDO::PARAM_STR);
        $stmt->bindParam(":desc", $this->Description, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->idProposal, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    /* delete proposal
     * return true or false
     * STATIC FUNCTION
     */
    static public function deleteProposal($db, $id)
    {
        $stmt = $db->prepare("DELETE FROM Proposal WHERE idProposal = :pid");
        $stmt->bindParam(":pid", $id, PDO::PARAM_INT);
        $stmt->execute();

        /*todo treba jos uraditi i da brise support i owners pomocu trigera*/
        return $stmt->rowCount() ? true : false;
    }

    /* add new proposal
     * return true or false
     * STATIC FUNCTION
     */
    static public function addProposal($db, $id, $name, $description)
    {
        $stmt = $db->prepare("call addProposal(:id, :name, :desc);");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":desc", $description, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    /* add user to proposal support
     * return true or false
     * STATIC FUNCTION
     */
    static public function addSupport($db, $idUser, $idProposal)
    {
        $stmt = $db->prepare("INSERT INTO ProposalSupport(user_id, idProposal) VALUES(:uid, :pid)");
        $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":pid", $idProposal, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    /* delete user from proposal support
     * return true or false
     * STATIC FUNCTION
     */
    static public function deleteSupport($db, $idUser, $idProposal)
    {
        $stmt = $db->prepare("DELETE FROM ProposalSupport WHERE user_id = :uid AND idProposal = :pid");
        $stmt->bindParam(":uid", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":pid", $idProposal, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    /* add user to proposal owners
     * return true or false
     * STATIC FUNCTION
     */
    static public function addOwner($db, $idPropose, $idProposal, $idProposed)
    {
        $stmt = $db->prepare("INSERT INTO ProposalOwner(UserPropose, idProposal, UserProposed) VALUES(:uid, :pid, :uid2)");
        $stmt->bindParam(":uid", $idPropose, PDO::PARAM_INT);
        $stmt->bindParam(":pid", $idProposal, PDO::PARAM_INT);
        $stmt->bindParam(":uid2", $idProposed, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    /* delete user from proposal owners
     * return true or false
     * STATIC FUNCTION
     */
    static public function deleteOwner($db, $idPropose, $idProposal, $idProposed)
    {
        $stmt = $db->prepare("DELETE FROM ProposalOwner WHERE UserPropose = :uid AND idProposal = :pid AND UserProposed = :uid2");
        $stmt->bindParam(":uid", $idPropose, PDO::PARAM_INT);
        $stmt->bindParam(":pid", $idProposal, PDO::PARAM_INT);
        $stmt->bindParam(":uid2", $idProposed, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() ? true : false;
    }

    /* get potential owners when Proposal->Activty
     * Return value is id's and number of votes for potential owners
     * STATIC FUNCTION
     */
    static public function getOwnersS($db, $id)
    {
        $stmt = $db->prepare("SELECT u.user_id, u.Name, u.Surname, Count(*) as 'Count' from ProposalOwner po join User u
                                                    on po.UserProposed = u.user_id
                              where idProposal = :id
                              group by u.user_id, u.Name, u.Surname
                              order by Name ASC, Surname ASC");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        if( $stmt->rowCount()  == 0)
        {
            return "Nema predloga za vlasnika! ";
        }
        else
        {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
    }

    /* get number of potential owners when Proposal->Activty
     * Return value is number of  potential owners
     * STATIC FUNCTION
     */
    static public function getOwnerCountS($db, $id)
    {
        $stmt = $db->prepare("SELECT COUNT(DISTINCT UserProposed) FROM ProposalOwner WHERE idProposal = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /* get all users who add vote for this proposal
     * Return value is array of user id's
     * STATIC FUNCTION
     */
    static public function getSupportS($db, $id)
    {
        $stmt = $db->prepare("SELECT u.user_id, u.Name, u.Surname from ProposalSupport ps join User u on ps.user_id = u.user_id                                   where idProposal = :id order by Name Desc, Surname Desc");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        if($stmt->rowCount() == 0)
            return "Niko ne podržava ovaj predlog!";
        else{
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $ret = array();
            foreach($result as $i){
                array_push($ret, $i);
            }
            return $ret;
        }
    }

    /* get number of users who add vote for this proposal
     * Return value is number of users
     * STATIC FUNCTION
     */
    static public function getSupportCountS($db, $id)
    {
        $stmt = $db->prepare("SELECT COUNT(*) FROM ProposalSupport WHERE idProposal = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    static public function getAllProposals($db)
    {
        $stmt = $db->prepare("SELECT p.idProposal, UserProposed, Name, Description, Count(*) as 'support_count' FROM Proposal p join proposalSupport ps on p.idProposal = ps.idProposal  GROUP BY idProposal, UserProposed, Name, Description ORDER BY idProposal DESC");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function is_support($db, $userId, $proposalId)
    {
        $stmt = $db->prepare("SELECT user_id FROM ProposalSupport WHERE idProposal = :proposal AND user_id = :user");
        $stmt->bindParam(":proposal", $proposalId, PDO::PARAM_INT);
        $stmt->bindParam(":user", $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() ? true : false;
    }

    static public function is_owner($db, $userId, $proposalId)
    {
        $stmt = $db->prepare("SELECT UserProposed FROM ProposalOwner WHERE idProposal = :proposal AND UserProposed = :user");
        $stmt->bindParam(":proposal", $proposalId, PDO::PARAM_INT);
        $stmt->bindParam(":user", $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() ? true : false;
    }

    static public function getAll($idProposal, $userId, $db)
    {
        $stmt = $db->prepare("Select concat(u.name, ' ', u.surname) as nameu, u.user_id, username, ps.user_id as support, po.userProposed as owner
                              from `user` u left outer join proposalOwner po on po.userProposed=u.user_id and po.idProposal=:idProp2 and po.UserPropose=:userPropose
                                    left outer join proposalSupport ps on ps.user_id=u.user_id and ps.idProposal=:idProp1
                                    order by nameu");
        $stmt->bindParam(":idProp2", $idProposal, PDO::PARAM_INT);
        $stmt->bindParam(":userPropose", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":idProp1", $idProposal, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    /*todo otkrivanje slicnih predloga*/
    /*todo trigeri za update i delete i insert*/
}
?>