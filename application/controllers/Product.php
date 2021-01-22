<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array(
			'Product_model',));
	}
	public function index()
	{
		$data['title'] = 'Product List';
		$data['product_list'] = $this->Product_model->productdetlist();
		//echo $this->db->last_query();
		$data['content'] = $this->load->view('product/prod-det-list', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}

	//check product dupolicacy for seller
	function checkProductDuplicacy()
	{
		$seller = $this->input->post('seller');
		$prod 	= $this->input->post('prod');
		$check = $this->db->select('*')->from('tbl_product_country')->join("tbl_proddetails", "tbl_proddetails.prodid = tbl_product_country.id", "LEFT")->where('country_name', "$prod")->where("tbl_proddetails.seller_id", $seller)->get()->result();
		if (!empty($check)) {
			echo json_encode(array('status' => 'exist'));
		} else {
			echo json_encode(array('status' => 'notexist'));
		}
	}
	function addproduct()
	{
		if (user_role('470') == true) {}
		if (isset($_POST['proname'])) {
			$this->saveProduct();
		}
		$data['title'] = 'Add Product';
		$data['category'] = $this->db->select("*")->where("comp_id", $this->session->companey_id)->get("tbl_category")->result();
		$currdate = date("Y-m-d");
		$data['scheme'] = $this->db->select("*")->where("comp_id", $this->session->companey_id)->where("from_date <= '$currdate' and to_date >= '$currdate'")->get("tbl_scheme")->result();
		$data['process'] = array(); //$this->db->select("*")->where("comp_id", $this->session->companey_id)->get("tbl_product")->result();
		$this->load->model('User_model');
		$this->load->model('warehouse_model');
		$data['seller_list'] = $this->User_model->read(200,true,false);
		$data['brand_list'] = $this->warehouse_model->brand_list();
		$data['units'] = $this->Product_model->get_units();
		$data['content']  = $this->load->view('product/add-product', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	function editproduct($prodno)
	{
		if (user_role('471') == true) {}
		if (isset($_POST['proname'])) {
			$this->saveProduct();
		}
		$data['units'] = $this->Product_model->get_units();
		$data['title'] = 'Update Product';
		$data['product'] = $this->Product_model->productdet($prodno);
		$data['category'] = $this->db->select("*")->where("comp_id", $this->session->companey_id)->get("tbl_category")->result();
		$data['subcategory'] = $this->db->select("*")->where("comp_id", $this->session->companey_id)->where("cat_id", $data["product"]->category)->get("tbl_subcategory")->result();
		$currdate = date("Y-m-d");
		$data['scheme'] = $this->db->select("*")->where("comp_id", $this->session->companey_id)->where("from_date <= '$currdate' and to_date >= '$currdate'")->get("tbl_scheme")->result();
		//print_r($data['scheme']);
		$data['process'] = array(); //$this->db->select("*")->where("comp_id", $this->session->companey_id)->get("tbl_product")->result();
		$this->load->model('User_model');
		$this->load->model('warehouse_model');
		$data['seller_list'] = $this->User_model->read(200);
		$data['brand_list'] = $this->warehouse_model->brand_list();
		$data['pid'] = $prodno;

		$data['content'] = $this->load->view('product/add-product', $data, true);
		//echo $data['content'];
		$this->load->view('layout/main_wrapper', $data);
	}
	public function addorder()
	{
		if (isset($_POST["proname"])) {
			$this->saveorder();
		}
		$data['title'] = 'Add Order';
		$data['products'] = $this->db->select("tbl_proddetails.*,tbl_product.*")
			->where("comp_id", $this->session->companey_id)
			->from("tbl_product")
			->join("tbl_proddetails", "tbl_product.sb_id = tbl_proddetails.prodid")->get()->result();
		$data['content'] = $this->load->view('product/add-order', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function orderlist()
	{
		if (user_role('460') == true) {}
		$data['title'] = 'Order List';
		$data['content'] = $this->load->view('order/order-list', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function saveorder()
	{
		$price 	  = $this->input->post("rate", true);
		$discount = $this->input->post("discount", true);
		$otrprice = $this->input->post("othrprice", true);
		$tax 	  = $this->input->post("tax", true);
		$total    = $price + $otrprice + $tax - $discount;
		$ordno    = "ORD" . strtotime("Y-m-d h:i:s");

		$insarr = array(
			"ord_no"  		=> $ordno,
			"cus_id"  		=> $this->session->user_id,
			"enq_no"  		=> "",
			"warehouse" 	=> "",
			"product"		=> $this->input->post("proname", true),
			"scheme"    	=> "",
			"quantity"		=> $this->input->post("quantity", true),
			"price"			=> $price,
			"other_price"	=> $otrprice,
			"total_price"	=> $total,
			"offer"			=> $discount,
			"details"		=> $this->input->post("details", true),
			"disc_meth"		=> "",
			"disc_price"	=> "",
			"disc_type" 	=> "",
			"tax"			=> $this->input->post("tax", true),
			"addedby"		=> "",
			"order_date"	=> date("Y-m-d h:i:s"),
			"status"		=> 1,
			"company"		=> $this->session->companey_id,
		);
		$ret = $this->db->insert("tbl_order", $insarr);
		if ($ret) {
			$this->session->set_flashdata('message', "Successfully saved");
		} else {
			$this->session->set_flashdata('exception', "Failed to saved");
		}
	}
	public function do_upload()
	{
		$config['upload_path']          = './assets/images/products/';
		$config['allowed_types']        = 'gif|jpg|png|jpeg';
		$config['max_size']      = '1024';
		$config['max_width']     = '1024';
		$config['max_height']    = '768';
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('mainimage')) {
			return array('error' => $this->upload->display_errors());
		} else {
			return array('upload_data' => $this->upload->data());
		}
	}
	public function saveProduct()
	{
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		$maxwidth = 1024;
		$maxheight = 768;
		$_FILES["mainimage"]["tmp_name"];
		// die();
		if (($_FILES["mainimage"]["size"] == 0 or $_FILES["mainimage"]["tmp_name"] == '') && empty($_POST["productid"])) {
			$this->session->set_flashdata('exception', 'Image Field is Required');
			redirect($_SERVER['REQUEST_URI'], 'refresh'); 
			exit();
		}
		$this->form_validation->set_rules('proname', display('product_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('price', 'Price', 'required|max_length[10]');
		$this->form_validation->set_rules('seller', 'Seller', 'required');
		$this->form_validation->set_rules('brand', 'Brand', 'required');
		if ($this->form_validation->run()) {
			$insarr = array(
				"country_name"  => $this->input->post("proname", true),
				"price"     	=> $this->input->post("price", true),
				"hsn_sac"	    => $this->input->post("hsn_code", true),
				"status"       => $this->input->post("status", true),
				"gst"			=> $this->input->post("tax", true),
				"minimum_order_quantity" => $this->input->post("minimum_order_quantity", true),
				"created_by"   => $this->session->user_id,
				"updated_date" => date("Y-m-d h:i:s"),
			);
			$file = $_FILES["mainimage"]['tmp_name'];
			if($file || empty($_POST["productid"])){
				list($imgwidth, $imgheight) = getimagesize($file);
				if ($imgwidth <= $maxwidth && $imgheight <= $maxheight) {
				} else {
					$this->session->set_flashdata('exception', 'max width of 1024 and max height of 768');
			        redirect($_SERVER['REQUEST_URI'], 'refresh'); 					
					exit();
				}
			}
			$seller_id = $this->input->post('seller');
			$brand = $this->input->post('brand');
			//$brand_row	=	$this->db->get_where('tbl_brand',array('id'=>$brand))->row_array();
			$this->load->model('User_model');
			$this->load->model('location_model');
			$seller_row = $this->User_model->read_by_id($seller_id);
			$seller_city = '';
			if (!empty($seller_row->city_id)) {
				$seller_city_row = $this->location_model->read_by_city($seller_row->city_id);
				$seller_city = $seller_city_row->city;
			}
			$skuid = 'LT-' . substr($brand, 0, 3) . '/' . substr($seller_city, 0, 3) . '/' . substr($seller_row->s_display_name, 0, 3);
			if (isset($_POST["productid"])) {
				$prodid  = $this->input->post("productid", true);
				$this->db->where("id", $prodid);
				$this->db->update("tbl_product_country", $insarr);
			} else {
				$insarr["comp_id"] 		=  $this->session->companey_id;
				$insarr["updated_by"]		=  $this->session->user_id;
				$insarr["created_date"]		=  date("Y-m-d h:i:s");
				$this->db->insert("tbl_product_country", $insarr);
				$prodid  = $this->db->insert_id();
			}
			if (isset($_POST['inputfieldno'])) {
				$inputno   = $this->input->post("inputfieldno", true);
				$enqinfo   = $this->input->post("enqueryfield", true);
				$inputtype = $this->input->post("inputtype", true);
				foreach ($inputno as $ind => $val) {
					$biarr = array(
						"product_id"  => $prodid,
						"input"   	  => $val,
						"parent"  	  => $prodid,
						"fvalue"  	  => (!empty($enqinfo[$ind])) ? $enqinfo[$ind] : "",
						"cmp_no"  	  => $this->session->companey_id,
					);
					$this->db->where('product_id', $prodid);
					$this->db->where('input', $val);
					$this->db->where('parent', $prodid);
					if ($this->db->get('product_fields')->num_rows()) {
						$this->db->where('product_id', $prodid);
						$this->db->where('input', $val);
						$this->db->where('parent', $prodid);
						$this->db->set('fvalue', $enqinfo[$ind]);
						$this->db->update('product_fields');
					} else {
						$this->db->insert('product_fields', $biarr);
					}
				}
			}
			$price = $this->input->post("price", true);
			$othrprice = (!empty($_POST["othrprice"])) ? $this->input->post("othrprice", true) : 0;
			$tax =  (!empty($_POST["tax"])) ? $this->input->post("tax", true) : 0;
			$total = $price + $othrprice + $tax;
			if (!empty($prodid)) {
				$err_msg = '';
				$imgarr   = $this->do_upload();
				$arr = array(
					"last_update"  => date("Y-m-d h:i:s"),
					// "image"		=> $imagename,
					"sub_image"    => "",
					"process"		=> $this->input->post("process"),
					"category"     => $this->input->post("cat", true),
					"subcatogory"  => $this->input->post("scat", true),
					"price"    	=> $price,
					"scheme"		=> $this->input->post("scheme", true),
					"othr_price"   => $othrprice,
					"tax"     		=> $tax,
					"total_price"  => $total,
					"size"         => $this->input->post("size", true),
					"weight"       => $this->input->post("weight", true),
					"color"        => $this->input->post("color", true),
					"details"      => $this->input->post("details", true),
					"hsn"			=> $this->input->post("hsn_code", true),
					"measurement_unit"	=> $this->input->post("measurement_unit", true),
					"seller_id"    => $seller_id,
					"skuid"    => $skuid,
					"brand"    => $brand
				);
				if (!empty($imgarr["upload_data"]["file_name"])) {
					$arr["image"] = $imgarr["upload_data"]["file_name"];
				} else {
					$err_msg = $this->upload->display_errors();
				}
				$sub_images	=	$this->upload_files('./assets/images/products/', 'image', $_FILES['sub_images']);
				if (!empty($sub_images)) {
					$arr['sub_image'] = json_encode($sub_images);
				}
				/*$ret = false;
				if (empty($err_msg)) {	*/
				if (isset($_POST["detailsid"])) {
					$detid = $this->input->post("detailsid", true);
					$this->db->where("id", $detid);
					$this->db->update("tbl_proddetails", $arr);
					$ret = true;
				} else {
					$arrp["stock"]    	= 0;
					$arrp["stockid"]  	= 0;
					$arr["prodid"]   	= $prodid;
					$ret = $this->db->insert("tbl_proddetails", $arr);
				}
				/*}*/
			}
			if ($ret) {
				$this->session->set_flashdata('message', "Successfully saved");
				//redirect(base_url("product"), "refresh");
			} else {
				$this->session->set_flashdata('exception', 'Something went wrong!');
			}
		}
	}
	public function upload_files($path, $title, $files)
	{
		/*print_r($files);
		exit();*/
		$config = array(
			'upload_path'   => $path,
			'allowed_types' => 'jpg|jpeg|gif|png',
			'remove_spaces' => TRUE,
			'encrypt_name'  => TRUE,
			'max_size'      => '1024',
			'max_width'     => '1024',
			'max_height'    => '768'
		);
		$this->load->library('upload', $config);
		$images = array();
		foreach ($files['name'] as $key => $image) {
			$_FILES['images[]']['name'] = $files['name'][$key];
			$_FILES['images[]']['type'] = $files['type'][$key];
			$_FILES['images[]']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['images[]']['error'] = $files['error'][$key];
			$_FILES['images[]']['size'] = $files['size'][$key];
			$fileName = $title . '_' . $image;
			$config['file_name'] = $fileName;
			$this->upload->initialize($config);
			if ($this->upload->do_upload('images[]')) {
				$data	=	$this->upload->data();
				$images[] = $data['file_name'];
			} else {
				return false;
			}
		}
		return $images;
	}
	function productlist()
	{
		if (user_role('60') == true) {
		}
		$data['title'] = display('product_list');
		$data['product_list'] = $this->Product_model->productlist();
		$data['content'] = $this->load->view('product/product_list', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	function stock()
	{
		if (isset($_FILES["csvupload"])) {
			$this->csvupload();
		}
		if (isset($_POST["downloadexel"])) {
			$sdate = $this->input->post('startdate', true);
			$sdate = (!empty($sdate)) ? date("Y-m-d", strtotime(str_replace("/", "-", $sdate))) : null;
			$edate = $this->input->post('enddate', true);
			$edate = (!empty($edate)) ? date("Y-m-d", strtotime(str_replace("/", "-", $edate))) : null;
			$this->downloadexel($sdate, $edate);
			echo "<script> window.location='" . base_url('product/stock') . "';</script>";
		}
		$data["title"] = "Stocks List";
		$data["stocks"] = $this->Product_model->getStock();
		$data["totalstock"] = $this->db->where("company", $this->session->userdata('companey_id'))->count_all_results("stock");
		$data["todaycreate"] = $this->db->where("company", $this->session->userdata('companey_id'))->where("stock_date", date("Y-m-d"))->count_all_results("stock");
		$data["todayupdate"] = $this->db->where("company", $this->session->userdata('companey_id'))->where("last_update", date("Y-m-d"))->count_all_results("stock");
		$data['content'] = $this->load->view('product/stock-list', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function addstock()
	{
		$this->savestock();
		$this->load->model("warehouse_model");
		$data['title'] = display('add_stock');
		$data['products'] = $this->Product_model->productlist();
		$data['brands'] = $this->warehouse_model->brand_list();
		$data['warehouse']    = $this->warehouse_model->warehouse_list();
		$data['content'] = $this->load->view('product/add-stock', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function updatestock($stkno = "")
	{
		$this->savestock();
		$data["products"] = $this->Product_model->getProduct();
		$stkno = base64_decode(base64_decode(urldecode($stkno)));
		$data["stock"] = $this->Product_model->getStockById($stkno);
		$data["warehouse"]  = $this->Product_model->getWarehouse();
		$data["supplier"]  = $this->Product_model->getSupplier();
		if (empty($data["stock"])) show_404();
		$data["title"] = "Update Stocks";
		$data['content'] = $this->load->view('product/update-stocks', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function datearg($post)
	{
		$date = $this->input->post($post, true);
		$ndate = str_replace("/", "-", $date);
		$dtarr = explode("-", $ndate);
		$ndate = (!empty($dtarr["2"])) ? $dtarr["1"] . "-" . $dtarr["0"] . "-" . $dtarr["2"] : NULL;
		return $ndate;
	}
	public function  savestock()
	{
		if (isset($_POST["product"])) {
			$this->form_validation->set_rules("product", "Product", "trim|required");
			$this->form_validation->set_rules("quantity", "Quantity", "trim|required");
			$this->form_validation->set_rules("price", "Price", "trim|required");
			if ($this->form_validation->run()) {
				$prdno = $this->input->post("product", true);
				$oldstock =  0;
				$stockarr =  $this->db->select("stock")->where("prodid", $prdno)->get("tbl_proddetails")->row();
				if (!empty($stockarr)) {
					$oldstock = $stockarr->stock;
				}
				$arr = array(
					"warehouse"  => $this->input->post("warehouse", true),
					"product"    => $this->input->post("product", true),
					"supplier"   => $this->input->post("supplier", true),
					"stock_date" => $this->datearg("stockdate"),
					"expiry"     =>  NULL,
					"details"    => $this->input->post("details", true),
					"quantity"   => $this->input->post("quantity", true),
					"price" 	  => $this->input->post("price", true),
					"minalert"   => $this->input->post("alertmin", true),
					"old_stock"  => (!empty($oldstock)) ? $oldstock : "",
				);
				if (isset($_POST["stockno"])) {
					$stockno = $this->input->post("stockno", true);
					$this->db->where("id", $stockno);
					$this->db->update("stock", $arr);
					$ret = $this->db->affected_rows();
				} else {
					$arr["added_by"]      = $this->session->user_id;
					$arr["company"]       =  $this->session->userdata('companey_id');
					$arr["last_update"]  = date("Y-m-d h:i:s");
					$arr["added_date"]   = date("Y-m-d h:i:s");
					$ret =  $this->db->insert("stock", $arr);
					$stockno = $this->db->insert_id();
					$ret = $this->db->affected_rows();
					if ($ret) {
						$newstock = $this->input->post("quantity", true);
						if ($oldstock > 0) {
							$newstock = $oldstock + $newstock;
						}
						$updarr = array("stock" => $newstock, "stockid" => $stockno);
						$totprd = $this->db->where("prodid", $prdno)->from("tbl_proddetails")->count_all_results();
						if ($totprd > 0) {
							$this->db->where("prodid", $prdno);
							$this->db->update("tbl_proddetails", $updarr);
						} else {
							$updarr["prodid"] = $prdno;
							$this->db->insert("tbl_proddetails",  $updarr);
						}
						$this->session->set_flashdata("message", "Successfully Saved stock");
						redirect(base_url("product/stock"), "refresh");
					} else {
						$this->session->set_flashdata("message", "Failed to Saved stock");
					}
				}
			} else {
				$this->session->set_flashdata("error", validation_errors());
			}
		}
	}
	public function downloadexel($sdate, $edate)
	{
		$this->load->library("excel");
		$stocks = $this->Product_model->getstockwithDate($sdate, $edate);
		//print_r($stocks);die;		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()
			->setCreator("Cona")
			->setLastModifiedBy($this->session->fname)
			->setTitle("Stock Information")
			->setSubject("Stock excel")
			->setDescription("Stock")
			->setKeywords("Stock");
		/*	$objPHPExcel->getActiveSheet()
            ->getStyle("A1:P1")
            ->getFont()
            ->setSize(18); */
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
		$rowarr = array("Sr No", "Product", "Old Stock", "Quantity", "Purchase price", "Supplier", "Stock Date");
		$objPHPExcel->setActiveSheetIndex(0);
		$ltr = 'A';
		foreach ($rowarr as $ind => $val) {
			$objPHPExcel->getActiveSheet()->setCellValue($ltr . "1", $val);
			$ltr++;
		}
		$pending   = $totprice = $totqty = 0;
		$count     = 1;
		if (!empty($stocks)) {
			foreach ($stocks as $ind => $ord) {
				$ltr = 'A';
				$count  =  $count + 1;
				$objPHPExcel->getActiveSheet()->SetCellValue(($ltr++) . $count, $count - 1)
					->SetCellValue(($ltr++) . $count, $ord->product_name)
					->SetCellValue(($ltr++) . $count, $ord->old_stock)
					->SetCellValue(($ltr++) . $count, $ord->quantity)
					->SetCellValue(($ltr++) . $count, $ord->price)
					->SetCellValue(($ltr++) . $count, $ord->supplier)
					->SetCellValue(($ltr++) . $count, date('d-M-Y', strtotime($ord->stock_date)));
			}
		}
		$objPHPExcel->getActiveSheet()->setTitle('Stock(' . count($stocks) . ')');
		$objPHPExcel->setActiveSheetIndex(0);
		$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$fname = "stocks_" . date("y_m_d") . ".xls";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $fname . '"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	public function csvupload()
	{
		$this->load->library("CsvRead");

		$stkarr = $this->csvread->readFile(8);
		$insarr = $errors =  array();
		foreach ($stkarr as $ind => $stk) {
			$warehouse  =  $stk['0'];
			$product    =  $stk['1'];
			$quantity   =  $stk['3'];
			$supplier   =  $stk['5'];
			if (empty($stk[0]) or empty($stk[1]) or empty($stk[2]) or empty($stk[3])) {
				$errors[] = "Check madatory fiels";
			}
			$wrhouse = $this->db->select("id")->where(array("name" => $warehouse, "comp_id" => $this->session->userdata("companey_id")))->get("tbl_warehouse");
			if ($wrhouse->num_rows() > 0) {
				$whid = $wrhouse->row()->id;
			} else {
				$errors[] = "Warehouse  <b>" . $warehouse . "</b> not founds";
				continue;
			}
			$prod = $this->db->select("id,stock")->where(array("pro_name" => $product, "comp_id" => $this->session->userdata("companey_id")))->where("pro_name", $product)->get("tbl_product");
			if ($prod->num_rows() > 0) {
				$prodid = $prod->row()->id;
				$oldstk = $prod->row()->stock;
			} else {
				$errors[] = "Product <b>" . $product . "</b> not founds";
				continue;
			}
			$splr = $this->db->select("id")->where(array("email" => $supplier, "company" => $this->session->userdata("companey_id")))->get("users");
			if ($splr->num_rows() > 0) {
				$splrid = $splr->row()->id;
			} else {
				$errors[] = "Supplier <b>" . $supplier . "</b> not founds";
				continue;
			}
			$insarr[] = array(
				"warehouse"   =>  $whid,
				"product"    =>  $prodid,
				"supplier"   =>   $splrid,
				"stock_date" =>   strtotime(str_replace("/", "-",  $stk[5])),
				//	 "expiry"     =>   strtotime(str_replace("/", "-",  $stk[6])),
				"details"    =>   $stk[2],
				"quantity"   =>   $stk[3],
				"price" 	  =>   $stk[4],
				"minalert"     => $stk[8],
				"old_stock"    => $oldstk,
				"added_by"     => $this->session->user_id,
				"company"      =>  $this->session->userdata("companey_id"),
				"last_update"  => date("Y-m-d h:i:s"),
				"added_date"   => date("Y-m-d h:i:s")
			);
		}
		$msg = "";
		if (count($errors) > 0) {
			$msg = count($errors) . "is failed to add. " . implode("<br />", $errors);
		}
		if (!empty($insarr)) {
			$ret = $this->db->insert_batch("stock", $insarr);

			$this->session->set_flashdata("message", "Successfully uploaded " . count($insarr) . " stock. " . $msg);
		} else {
			$this->session->set_flashdata("error", "Failed to uploaded user. " . $msg);
		}
	}

	public function category($categ = "")
	{

		if (isset($_POST["categid"])) {
			$this->savesubcateg();
			redirect(base_url("product/category/" . $categ), "refresh");
		} else if (isset($_POST["category"])) {
			$this->savecateg();
			redirect(base_url("product/category.html"), "refresh");
		}
		if (empty($categ)) {
			$data["title"] 	  = "Add Category";
			$data['category'] = $this->db->select("*")->where("comp_id", $this->session->companey_id)->get("tbl_category")->result();
			$data["categid"]     = false;
		} else {
			$data["title"] = "Add Subcategory";
			$data['category'] = $this->db->select("id,subcat_name as name,status")
				->where("cat_id", $categ)->where("comp_id", $this->session->companey_id)->get("tbl_subcategory")->result();
			$data["categid"]     = $categ;
		}
		$data['content'] = $this->load->view('product/categ-list', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function savecateg()
	{
		$this->form_validation->set_rules("category", "Category", "trim|required");
		if ($this->form_validation->run()) {
			$arr = array(
				"comp_id" 		=> $this->session->companey_id,
				"type"	   		=> 1,
				"name"	   		=> $this->input->post("category", true),
				"description"  => "",
				"status"		=> 1,
				"created_date"	=> date("Y-m-d h:i:s"),
				"updated_date" => date("Y-m-d h:i:s")
			);
			$ret = $this->db->insert("tbl_category", $arr);
			if ($ret) {
				$this->session->set_flashdata("message", "Successfully saved category");
			} else {
				$this->session->set_flashdata("error", "Failed to saved category");
			}
		} else {
			$this->session->set_flashdata("error", validation_errors());
		}
	}
	public function savesubcateg()
	{
		$this->form_validation->set_rules("category", "Category", "trim|required");
		if ($this->form_validation->run()) {
			$arr = array(
				"comp_id" 		=> $this->session->companey_id,
				"subcat_name"	   		=> $this->input->post("category", true),
				"cat_id"       =>  $this->input->post("categid", true),
				"status"		=> 1,
				"created_date"	=> date("Y-m-d h:i:s"),
				"created_by" => $this->session->user_id
			);
			$ret = $this->db->insert("tbl_subcategory", $arr);
			if ($ret) {
				$this->session->set_flashdata("message", "Successfully saved subcategory");
			} else {
				$this->session->set_flashdata("error", "Failed to saved category");
			}
		} else {
			$this->session->set_flashdata("error", validation_errors());
		}
	}

	public function stockupdate($stkno)
	{
		$this->savestock();
		$data["products"] = $this->Product_model->getProduct();
		$stkno = base64_decode(base64_decode(urldecode($stkno)));
		$data["stock"] = $this->Product_model->getStockById($stkno);
		$this->load->model("warehouse_model");
		$data["warehouse"]  = $this->warehouse_model->warehouse_list1();
		if (empty($data["stock"])) show_404();
		$data["title"] = "Update Stocks";
		$data['content'] = $this->load->view('product/update-stock', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}
	public function loadstock()
	{

		$stockarr = $this->Product_model->stock(1);
		$rows  = array();
		if (!empty($stockarr)) {
			foreach ($stockarr as $ind => $stk) {
				$cols = array();
				$srno   = $ind + 1;
				$image  = (!empty($stk->main_img)) ? base_url($stk->main_img)  : base_url("assets/images/products/33.png");
				$cols[] = '<label class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" name="example-checkbox1" value="' . $stk->id . '"><span class="custom-control-label"> ' . $srno . '</span></label>';
				$cols[] = ucwords($stk->product_name);
				$cols[] = $stk->quantity . "<br /> <span class = 'badge badge-primary'> Old Stock: " . $stk->old_stock . " </span>";
				$cols[] = (!empty($stk->price)) ? $stk->price : " ";
				$cols[] = (!empty($stk->supplier)) ? $stk->supplier : " ";
				$cols[] = date("m/d/Y", strtotime($stk->stock_date));

				$cols[] =  '<a href="' . base_url("product/stockupdate/" . urlencode(base64_encode(base64_encode($stk->id)))) . '" class="btn btn-info">
								<i class="fa fa-pencil" data-toggle="tooltip" title="" data-original-title="Edit"></i></a><a href="' . base64_encode(base64_encode($stk->id)) . '"  class="btn btn-danger delete-stocks"><i class="fa fa-trash" data-toggle="tooltip" title="" data-original-title="Delete"></i></a>';
				$rows[] = $cols;
			}
		}
		$total = $this->Product_model->stock(2);
		$output = array(
			"draw" => @$_POST['draw'],
			"recordsTotal" 		=> $total,
			"recordsFiltered"   => $total,
			"data" => $rows,
		);
		die(json_encode($output));
	}
	function get_region_byid()
	{
		$country_id = $this->input->post('country_id');
		$data['country'] = $this->location_model->get_region_byid($country_id);
		echo '<option value="" selected>Select</option>';
		foreach ($data['country'] as $r) {
			echo '<option value="' . $r->region_id . '">' . $r->region_name . '</option>';
		}
	}
	function get_state_byid()
	{
		$country_id = $this->input->post('country_id');
		$region_id = $this->input->post('region_id');
		$data['country'] = $this->location_model->get_state_byid($country_id, $region_id);
		echo '<option value="" selecte>Select</option>';
		foreach ($data['country'] as $r) {
			echo '<option value="' . $r->id . '">' . $r->state . '</option>';
		}
	}
	function get_city_byid()
	{
		$state_id = $this->input->post('state_id');
		$data['country'] = $this->location_model->get_city_byid($state_id);
		echo '<option value="" style="display:none">---Select City---</option>';
		foreach ($data['country'] as $r) {
			echo '<option value="' . $r->id . '">' . $r->city . '</option>';
		}
	}
	function get_product()
	{
		//slected_product_id
		$fellowid1 = $this->input->post('fellowid1');
		$product_id = $this->input->post('selected_product_id');
		echo '<option value="" style="display:none">---Select Product---</option>';
		$myFellowProducts = $this->User_model->myFellowProductIds($fellowid1);
		$fellow_product_list = [];
		if (isset($myFellowProducts->product_id) && ($myFellowProducts->product_id != '')) {
			$fellowProductIds = explode(',', $myFellowProducts->product_id);
			$fellow_product_list = $this->Product_model->product_list_by_id($fellowProductIds);
		}
		foreach ($fellow_product_list as $fellowObj) {
			$selected = '';
			if (isset($product_id) && $product_id == $fellowObj->product_id) {
				$selected = "selected";
			}
			echo '<option ' . $selected . ' value="' . $fellowObj->product_id . '">' . $fellowObj->product_name . '</option>';
		}
	}
	public function add_product()
	{
		if (user_role('470') == true) {}
		$this->load->model("location_model");
		$data['title'] = display('add_product');
		$data['product'] = '';
		$this->form_validation->set_rules('product_name', display('product_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('country_id', display('country_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('region_id', display('region_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('state_id', display('state_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('city_id', display('city_name'), 'required|max_length[50]');
		$this->form_validation->set_rules('block_id', display('block_name'), 'required|max_length[50]');
		$data['product'] = (object) $postData = [
			'product_id' => $this->input->post('product_id', true),
			'dise_code' => $this->input->post('dise_code', true),
			'product_name' => $this->input->post('product_name', true),
			'contact_name' => $this->input->post('contact_name', true),
			'contact_number' => $this->input->post('contact_number', true),
			'address' => $this->input->post('address', true),
			'block_id' => $this->input->post('block_id', true),
			'city_id' => $this->input->post('city_id', true),
			'state_id' => $this->input->post('state_id', true),
			'country_id' => $this->input->post('country_id', true),
			'region_id' => $this->input->post('region_id', true),
			'status' => $this->input->post('status', true),
			'created_by' => $this->session->userdata('user_id'),
			'updated_by' => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d'),
			'updated_date' => date('Y-m-d'),
			'ipaddress' => $_SERVER['REMOTE_ADDR']
		];
		if ($this->form_validation->run() === true) {
			if (empty($this->input->post('product_id'))) {
				if (user_role('20') == true) {
				}
				if ($this->Product_model->insertRow($postData)) {
					$this->session->set_flashdata('message', display('save_successfully'));
				} else {
					$this->session->set_flashdata('exception', display('please_try_again'));
				}
			} else {
				if (user_role('21') == true) {
				}
				if ($this->Product_model->updateRow($postData)) {
					$this->session->set_flashdata('message', display('update_successfully'));
				} else {
					$this->session->set_flashdata('exception', display('please_try_again'));
				}
			}
			redirect('product/productlist');
		} else {
			$data['country'] = $this->location_model->country();
			$data['region_list'] = $this->location_model->region_list();
			$data['state_list'] = $this->location_model->state_list();
			$data['city_list'] = $this->location_model->city_list();
			$data['block_list'] = $this->location_model->block_list();
			$data['partial'] = 'product/product_form';
			$this->load->view('layout/main_wrapper', $data);
		}
	}
	public function edit_product($product_id = null)
	{
		if (user_role('471') == true) {}
		$data['title'] = display('update_product');
		$data['product'] = $this->Product_model->readRow($product_id);
		$data['city_list'] = $this->location_model->city_list();
		$data['state_list'] = $this->location_model->state_list();
		$data['country'] = $this->location_model->country();
		$data['region_list'] = $this->location_model->region_list();
		//   $data['block_list'] = $this->location_model->block_list();
		$data['partial'] = 'product/product_form';
		$this->load->view('layout/main_wrapper', $data);
	}
	public function contact_master($product_id = null)
	{
		if (user_role('21') == true) {
		}
		$data['title'] = display('contact_master');
		$data['partial'] = 'product/subject_from';
		$this->load->view('layout/main_wrapper', $data);
	}
	public function delete_product_category()
	{
		$cat = $this->input->post('cat');
		if (!empty($cat)) {
			foreach ($cat as $key => $value) {
				$this->db->where('id', $value);
				if (!empty($this->input->post('categid'))) {
					$this->db->delete('tbl_subcategory');
				} else {
					$this->db->delete('tbl_category');
				}
			}
		}
	}
	public function delete_product_unit()
	{
		$unit = $this->input->post('unit');
		if (!empty($unit)) {
			foreach ($unit as $key => $value) {
				$this->db->where('id', $value);
				$this->db->delete('measurement_unit');
			}
		}
	}
	public function measurement_unit()
	{
		if (user_role('440') == true) {}
		if (!empty($_POST)) {
			$unit	=	$this->input->post('unit');
			$insarr = array(
				'comp_id' => $this->session->companey_id,
				'title'   => $unit,
				'created_by' => $this->session->user_id
			);
			if ($this->db->insert('measurement_unit', $insarr)) {
				$this->session->set_flashdata('message', 'Unit Created Successfully.');
			}
		}
		$data = array();
		$data['title'] = "Measurement Unit";
		$data['units'] = $this->Product_model->get_units();
		$data['content']	=	$this->load->view('product/measurement_unit', $data, true);
		$this->load->view('layout/main_wrapper', $data);
	}

}
