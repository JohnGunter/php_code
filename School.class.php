<?php

class School {
	
	/*
		@var int to store the school id
	*/
	protected $SchoolID;
	
	/*
		@var array Sstores the school data in a PHP Object
	*/
	protected $SchoolData;
	
	public function __construct($school_id = 0) {
		$this->SchoolID = $school_id;
		$this->db = DatabaseAbstraction::GetConnection();
        $this->SchoolData = [];
	}
	
	public function GetSchoolID() {
		return $this->SchoolID;
	}
	
	public function SearchForSchoolData() {
		$query = 'SELECT name,pac_grp,ext_grp,ring_grp,logo_group_id,hide_special_college,hide_degree1,hide_degree2,hide_major1,hide_major2,hide_honor1,hide_honor2,hide_ctime,hide_cdate,hide_clocation,df_shipping,df_price,df_siz_grp_id,df_frame_grp_id,df_design_grp,df_molding_grp,capgown_available,alternate_order_form,imgFolder,email_bcc FROM schools WHERE id = :school_id';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue('school_id', $this->SchoolID);
		$stmt->execute();
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach( $res as $key=>$value ) {
			$SchoolData[$key] = $value;
		}
		return $SchoolData;
	}
	
}

?>