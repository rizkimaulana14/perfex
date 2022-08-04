<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_125 extends App_module_migration
{
	public function up()
	{   
		$CI = &get_instance();

		//Check if Version 122 is not available yet
		if (!$CI->db->field_exists('sub_total' ,db_prefix() . 'goods_receipt_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_receipt_detail`
				ADD COLUMN `sub_total` DECIMAL(15,2) NULL DEFAULT '0'
				;");
		}

		if (!$CI->db->field_exists('tax_name' ,db_prefix() . 'goods_receipt_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_receipt_detail`
				ADD COLUMN `tax_name` TEXT NULL
				;");
		}

		if (!$CI->db->field_exists('commodity_name' ,db_prefix() . 'internal_delivery_note_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "internal_delivery_note_detail`
				ADD COLUMN `commodity_name` TEXT NULL
				;");
		}

		if (!$CI->db->field_exists('commodity_name' ,db_prefix() . 'wh_loss_adjustment_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_loss_adjustment_detail`
				ADD COLUMN `commodity_name` TEXT NULL
				;");
		}
		if (!$CI->db->field_exists('tax_rate' ,db_prefix() . 'goods_delivery_detail')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery_detail`
				ADD COLUMN `tax_rate` TEXT NULL,
				ADD COLUMN `tax_name` TEXT NULL,
				ADD COLUMN `sub_total` DECIMAL(15,2) NULL DEFAULT '0'
				;");
		}
		if (!$CI->db->field_exists('additional_discount' ,db_prefix() . 'goods_delivery')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery`
				ADD COLUMN `additional_discount` DECIMAL(15,2) NULL DEFAULT '0'
				;");
		}

		if (!$CI->db->field_exists('sub_total' ,db_prefix() . 'goods_delivery')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery`
				ADD COLUMN `sub_total` DECIMAL(15,2) NULL DEFAULT '0'
				;");
		}

		//Check if Version 123 is not available yet
		add_option('goods_delivery_pdf_display_warehouse_lotnumber_bottom_infor', 0, 1);
	
		//Check if Version 124 is not available yet
		add_option('packing_list_number_prefix', 'PL', 1);
		add_option('next_packing_list_number', 1, 1);

		if (!$CI->db->table_exists(db_prefix() . 'wh_packing_lists')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . "wh_packing_lists` (

				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`delivery_note_id` INT(11) NULL,
				`packing_list_number` VARCHAR(100) NULL,
				`packing_list_name` VARCHAR(200) NULL,
				`width` DECIMAL(15,2) NULL DEFAULT '0.00',
				`height` DECIMAL(15,2) NULL DEFAULT '0.00',
				`lenght` DECIMAL(15,2) NULL DEFAULT '0.00',
				`weight` DECIMAL(15,2) NULL DEFAULT '0.00',
				`volume` DECIMAL(15,2) NULL DEFAULT '0.00',
				`clientid` INT(11) NULL,
				`subtotal` DECIMAL(15,2) NULL DEFAULT '0.00',
				`total_amount` DECIMAL(15,2) NULL DEFAULT '0.00',
				`discount_total` DECIMAL(11) NULL DEFAULT '0.00',
				`additional_discount` DECIMAL(11) NULL DEFAULT '0.00',
				`total_after_discount` DECIMAL(11) NULL DEFAULT '0.00',
				`billing_street` varchar(200) DEFAULT NULL,
				`billing_city` varchar(100) DEFAULT NULL,
				`billing_state` varchar(100) DEFAULT NULL,
				`billing_zip` varchar(100) DEFAULT NULL,
				`billing_country` int(11) DEFAULT NULL,
				`shipping_street` varchar(200) DEFAULT NULL,
				`shipping_city` varchar(100) DEFAULT NULL,
				`shipping_state` varchar(100) DEFAULT NULL,
				`shipping_zip` varchar(100) DEFAULT NULL,
				`shipping_country` int(11) DEFAULT NULL,
				`client_note` TEXT NULL,
				`admin_note` TEXT NULL,
				`approval` INT(11) NULL DEFAULT 0,
				`datecreated` DATETIME NULL,
				`staff_id` INT(11) NULL,

				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
			}

			if (!$CI->db->table_exists(db_prefix() . 'wh_packing_list_details')) {
				$CI->db->query('CREATE TABLE `' . db_prefix() . "wh_packing_list_details` (

					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`packing_list_id` INT(11) NOT NULL,
					`delivery_detail_id` INT(11) NULL,
					`commodity_code` INT(11) NULL,
					`commodity_name` TEXT NULL,
					`quantity` DECIMAL(15,2) NULL DEFAULT '0.00',
					`unit_id` INT(11) NULL,
					`unit_price` DECIMAL(15,2) NULL DEFAULT '0.00',
					`sub_total` DECIMAL(15,2) NULL DEFAULT '0.00',
					`tax_id`  TEXT NULL,
					`tax_rate`  TEXT NULL,
					`tax_name`  TEXT NULL,
					`total_amount` DECIMAL(15,2) NULL DEFAULT '0.00',
					`discount` DECIMAL(11) NULL DEFAULT '0.00',
					`discount_total` DECIMAL(11) NULL DEFAULT '0.00',
					`total_after_discount` DECIMAL(11) NULL DEFAULT '0.00',

					PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
			}

			if (!$CI->db->field_exists('packing_qty' ,db_prefix() . 'goods_delivery_detail')) { 
				$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery_detail`
				ADD COLUMN `packing_qty` DECIMAL(15,2) NULL DEFAULT '0.00'
				;");
			}


		
		
		//Version 125
		if (!$CI->db->field_exists('type_of_packing_list' ,db_prefix() . 'wh_packing_lists')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_packing_lists`
				ADD COLUMN `type_of_packing_list` VARCHAR(100)  NULL DEFAULT 'total'
				;");
		}

		if (!$CI->db->field_exists('delivery_status' ,db_prefix() . 'wh_packing_lists')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "wh_packing_lists`
				ADD COLUMN `delivery_status` VARCHAR(100)  NULL DEFAULT 'wh_ready_to_deliver'
				;");
		}

		if (!$CI->db->field_exists('delivery_status' ,db_prefix() . 'goods_delivery')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "goods_delivery`
				ADD COLUMN `delivery_status` VARCHAR(100)  NULL DEFAULT 'ready_for_packing'
				;");
		}

		// purchase, => can_be_purchased
		// inventory => can_be_inventory
		// loyalty => can_be_sold
		// omni_sale => can_be_sold
		// sale_invoice => can_be_sold
		// manufacturing order => can_be_manufacturing
		// affiliate => can_be_sold

		if (!$CI->db->field_exists('can_be_sold' ,db_prefix() . 'items')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "items`
				ADD COLUMN `can_be_sold` VARCHAR(100) NULL DEFAULT 'can_be_sold'
				;");
		}
		if (!$CI->db->field_exists('can_be_purchased' ,db_prefix() . 'items')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "items`
				ADD COLUMN `can_be_purchased` VARCHAR(100) NULL DEFAULT 'can_be_purchased' 
				;");
		}
		if (!$CI->db->field_exists('can_be_manufacturing' ,db_prefix() . 'items')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "items`
				ADD COLUMN `can_be_manufacturing` VARCHAR(100) NULL DEFAULT 'can_be_manufacturing' 
				;");
		}

		if (!$CI->db->field_exists('can_be_inventory' ,db_prefix() . 'items')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "items`
				ADD COLUMN `can_be_inventory` VARCHAR(100) NULL DEFAULT 'can_be_inventory' 
				;");
		}

		//add shipment on Omnisales module
		if (!$CI->db->table_exists(db_prefix() . 'wh_omni_shipments')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . "wh_omni_shipments` (

				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`cart_id` INT(11) NULL,
				`shipment_number` VARCHAR(100) NULL,
				`planned_shipping_date` DATETIME NULL,
				`shipment_status` VARCHAR(50) NULL,
				`datecreated` DATETIME NULL,

				PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
			}

		}
	}
