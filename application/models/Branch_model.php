<?php
class Branch_model extends CI_model
{
	
	public function add_vehicle_type($data)
	{
		$this->db->insert('vehicle',$data);
		return $this->db->insert_id();
	}

	public function save_vehicle_type($id,$data)
	{
		$this->db->where('vehicle_type_id',$id);
		$this->db->update('vehicle',$data);
	}

	public function delete_vehicle($where,$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;
		$this->db->where('comp_id',$comp_id);
		$this->db->where($where)->delete('vehicle');
	}

	public function get_vehicles($id=0,$where=array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id= $this->session->companey_id;

		if(!empty($where))
			$this->db->where($where);

		if(!empty($id))
			$this->db->where('vehicle_type_id',$id);

		return $this->db->get('vehicle');
	}

	public function save_zone($data,$id=0)
	{
		if($id)
		{
			$this->db->where('zone_id',$id);
			$this->db->update('zones',$data);
		}
		else
		{
			$this->db->insert('zones',$data);
			return $this->db->insert_id();
		}
	}


	public function save_region($data,$id=0)
	{
		if($id)
		{
			$this->db->where('region_id',$id);
			$this->db->update('sales_region',$data);
		}
		else
		{
			$this->db->insert('sales_region',$data);
			return $this->db->insert_id();
		}
	}

	public function save_area($data,$id=0)
	{
		if($id)
		{
			$this->db->where('area_id',$id);
			$this->db->update('sales_area',$data);
		}
		else
		{
			$this->db->insert('sales_area',$data);
			return $this->db->insert_id();
		}
	}

	public function zone_list($id=0,$where=array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id= $this->session->companey_id;

		if(!empty($where))
			$this->db->where($where);

		if(!empty($id))
			$this->db->where('zone_id',$id);
		$this->db->where('comp_id',$comp_id);
		return $this->db->get('zones');
	}

	public function sales_region_list($id=0,$where=array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id= $this->session->companey_id;

		if(!empty($where))
			$this->db->where($where);

		if(!empty($id))
			$this->db->where('region_id',$id);
		$this->db->where('comp_id',$comp_id);
		return $this->db->get('sales_region');
	}

	public function sales_area_list($id=0,$where=array(),$comp_id=0)
	{
		$this->db->select('sales_area.*,sales_region.name as region_name')
					->from('sales_area')
					->join('sales_region','sales_region.region_id=sales_area.region_id');

		if(empty($comp_id))
			$comp_id= $this->session->companey_id;

		if(!empty($where))
			$this->db->where($where);

		if(!empty($id))
			$this->db->where('sales_area.area_id',$id);
		$this->db->where('sales_area.comp_id',$comp_id);
		return $this->db->get();
	}

	public function delete_zone($where,$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;
		$this->db->where('comp_id',$comp_id);
		$this->db->where($where)->delete('zones');
	}

	public function delete_region($where,$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;
		$this->db->where('comp_id',$comp_id);
		$this->db->where($where)->delete('sales_region');
	}

	public function branch_list($id=0,$where=array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id= $this->session->companey_id;

		if(!empty($where))
			$this->db->where($where);

		if(!empty($id))
			$this->db->where('branch.branch_id',$id);
		$this->db->select('branch.*,area.area_name,region.name region_name');
		$this->db->from('branch');
		$this->db->join('sales_area area','area.area_id=branch.area_id','left');
		$this->db->join('sales_region region','region.region_id=branch.region_id','left');
		$this->db->where('branch.type','branch');
		$this->db->where('branch.comp_id',$comp_id);
		return $this->db->get();
	}

	public function common_list($where=array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id= $this->session->companey_id;

		if(!empty($where))
			$this->db->where($where);

		$this->db->select('branch.*');
		$this->db->from('branch');
		$this->db->where('branch.comp_id',$comp_id);
		return $this->db->get();
	}

	public function rate_list($type='branch',$where= array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;
		if($type=='branch')
		{
			$this->db->select('rate.*,b1.branch_name from,b2.branch_name to')
					->from('branchwise_rate rate')
					->join('branch b1','b1.branch_id=rate.booking_branch')
					->join('branch b2','b2.branch_id=rate.delivery_branch');
		}
		else if($type=='zone')
		{
			$this->db->select('rate.*,b1.name from,b2.name to')
					->from('branchwise_rate rate')
					->join('zones b1','b1.zone_id=rate.booking_branch')
					->join('zones b2','b2.zone_id=rate.delivery_branch');
		}
		if(!empty($where))
			$this->db->where($where);
		$this->db->where('rate.type',$type);
		$this->db->where('rate.comp_id',$comp_id);
		return $this->db->get();
		
	}

	public function from_to_table($from=array(),$to=array(),$type='branch',$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;

		$this->db->select('rate.*,b1.branch_name bbranch,b1.type btype,b2.branch_name dbranch,b2.type dtype')
				->from('branchwise_rate rate')
				->join('branch b1','b1.branch_id=rate.booking_branch')
				->join('branch b2','b2.branch_id=rate.delivery_branch');
		if(!empty($from))
		$this->db->where_in('rate.booking_branch',$from);
		if(!empty($to))
			$this->db->where_in('rate.delivery_branch',$to);
		$this->db->where('rate.comp_id',$comp_id);
		return $this->db->get();
		
	}

	function add_deal($data)
	{
		$this->db->insert('commercial_info',$data);
		return $this->db->insert_id();
	}
	
	function add_deal_data($data)
	{
		$this->db->insert('deal_data',$data);
		return $this->db->insert_id();
	}
	public function get_deal($id,$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;

			$this->db->where('id',$id);
			$this->db->where('comp_id',$comp_id);
		return $this->db->get('commercial_info')->row();

	}

	public function get_deal_data($deal_id)
	{
		$this->db->select('v.type_name vtype_name,b1.branch_name as bbranch,b2.branch_name as dbranch,deal_data.*');
		$this->db->from('deal_data');
		$this->db->join('branch b1','b1.branch_id=deal_data.booking_branch','left');
		$this->db->join('branch b2','b2.branch_id=deal_data.delivery_branch','left');
		$this->db->join('vehicle v','deal_data.vehicle_type=v.vehicle_type_id','left');
		$this->db->where('deal_data.deal_id',$deal_id);
		return $this->db->get()->result();
	}

	public function discount_list($id=0,$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;

		if($id)
		$this->db->where('id',$id);
		$this->db->where('comp_id',$comp_id);
	return	$this->db->get('discount_matrix')->result();
	}
	public function oda_list($id=0,$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;

		if($id)
		$this->db->where('id',$id);
		$this->db->where('comp_id',$comp_id);
	return	$this->db->get('oda_matrix')->result();
	}

	public function bank_list($id=0,$where=array(),$comp_id=0)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;

		if($id)
		$this->db->where('b.id',$id);
		$this->db->where('b.comp_id',$comp_id);
		$this->db->select('b.*,z.name as zone_name')
				->from('bank_details b')
				->join('zones z','z.zone_id=b.zone_id','left');
				;
	return	$this->db->get()->result();
	}
	public function bank_by_zone($zoneid)
	{
		if(empty($comp_id))
			$comp_id = $this->session->companey_id;

		$this->db->where('b.comp_id',$comp_id);
		$this->db->select('b.*,z.name as zone_name')
				->from('bank_details b')
				->join('zones z','z.zone_id=b.zone_id','left')
				->where('b.zone_id',$zoneid);
				
		return	$this->db->get()->row();
	}

	public function deal_list($where=array())
	{
		if(!empty($where))
			$this->db->where($where);
		
		$this->db->where('comp_id',$this->session->companey_id);
		return $this->db->get('commercial_info')->result();
	}
}