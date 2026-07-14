<?php 
$base_url = '#';

return[

		// 'application_name_en' => env('APP_NAME', true),
		// 'application_name_np' => env('APP_NAME', true),
		// 'office_name_en' => '',
		// 'office_name_np' => '',
		'activity_log' => env('ACTIVITY_LOG', true),
		
		// 'show_recaptcha' => env('RECAPTCHA_ENABLE', false),
		'front_template' => env('FRONT_TEMPLATE', 'template1'),
		// 'sms_providers' => [
		// 	'sparrow_sms' => 'Sparrow SMS',
		// 	'aakash_sms'  => 'Aakash SMS'
		// ],
		// 'mail_notice' => env('MAIL_NOTICE', true),
		// 'sms_notice' => env('SMS_NOTICE', false),
		// 'sms_provider' => env('SMS_PROVIDER', 'aakash_sms'),
		// 'sms_token' => env('SMS_TOKEN', 'null'),
        // 'sms_from'  => env('SMS_IDENTITY', 'null'),
	    'allowedfileExtension' => ['JPG','JPEG','PNG','GIF','PDF','TXT','DOCX','MP3','MP4','jpeg','bmp','png','jpg','gif','pdf','txt','docx','mp3','3gp','mp4'],
	    // 'allowedfileSize' => '5120k',

	    // 'mail_from'      => env('MAIL_FROM', ''),
	    // 'mail_from_name' => env('MAIL_FROM_NAME', ''),


		// 'menu_types' => [
		// 	'page_menu' => 'Page Menu',
		// 	'news_menu' => 'New Category Menu',
		// 	'internal_link' => 'Internal Link Menu',
		// 	'external_link' => 'External Link Menu',
		// ],

		// 'marital_status' => [
		// 	'unmarried' => 'अविवाहित',
		// 	'married' => 'विवाहित',
		// 	'divorcee' => 'सम्बन्धविच्छेद',
		// 	'widow' => 'एकल महिला',
		// 	'widower' => 'एकल पुरुष',
		// 	'legally_separated' => 'कानूनी रूपमा अलग',
		// ],
		// 'gender' => [
		// 	'male' => 'पुरुष',
		// 	'female' => 'महिला',
		// 	'others' => 'अन्य',
		// ],
		// 'withness_relation' => [
		// 	'father' => 'बाबु',
		// 	'mother' => 'आमा',
		// 	'husband' => 'पति/पत्नी',
		// ],
		// 'withness_relation_details' => [
		// 	'father' => [
		// 		'name_np' => 'बाबु',
		// 		'name_en' => 'Father',
		// 		'applicant_male_relation_np' => 'का छोरा',
		// 		'applicant_male_relation_en' => 'Son',
		// 		'applicant_female_relation_np' => 'का छोरी',
		// 		'applicant_female_relation_en' => 'daughter',
		// 	],
		// 	'mother' => [
		// 		'name_np' => 'आमा',
		// 		'name_en' => 'Mother',
		// 		'applicant_male_relation_np' => 'का छोरा',
		// 		'applicant_male_relation_en' => 'Son',
		// 		'applicant_female_relation_np' => 'का छोरी',
		// 		'applicant_female_relation_en' => 'daughter',
		// 	],
		// 	'husband' => [
		// 		'name_np' => 'पति/पत्नी',
		// 		'name_en' => 'Spouse',
		// 		'applicant_male_relation_np' => 'को श्रीमान',
		// 		'applicant_male_relation_en' => 'Husband',
		// 		'applicant_female_relation_np' => 'को श्रीमती',
		// 		'applicant_female_relation_en' => 'Wife',
		// 	],
		// ],
		// 'quadrimester' => [
		// 	'first_quad' => 'प्रथम चौमासिक',
		// 	'second_quad' => 'दोस्रो चौमासिक',
		// 	'third_quad' => 'तेस्रो चौमासिक'
		// ],

		// 'family_type' => [
		// 	'join' => 'संयुक्त',
		// 	'single' => 'एकल',
		// ],

		// 'after_training_status' => [
		// 	'seld_employment' => 'स्वरोजगारी',
		// 	'employment' => 'रोजगारी / ज्याला रोजगारी',
		// 	'limited_to_home' => 'घरयासी प्रयोजनमा सिमित',
		// 	'not_used' => 'सदुपयोग छैन',
		// ],
		// 'fiscal_year' => [
		// 	'077-078' => '०७७/०७८',
		// 	'078-079' => '०७८/०७९',
		// 	'079-080' => '०७९/०८०',
		// 	'080-081' => '०८०/०८१',
		// 	'081-082' => '०८१/०८२',
		// 	'082-083' => '०८२/०८३',
		// 	'083-084' => '०८३/०८४',
		// ],
		// 'link_types' => [
		// 	'province' => [
		// 		'type' => 'province',
		// 		'name_np' => 'प्रदेश तर्फ',
		// 		'name_en' => 'Provincial',
		// 	],
		// 	'federal' => [
		// 		'type' => 'federal',
		// 		'name_np' => 'संघ तर्फ',
		// 		'name_en' => 'Fedearl',
		// 	],
		// 	'other' => [
		// 		'type' => 'other',
		// 		'name_np' => 'अन्य',
		// 		'name_en' => 'Other',
		// 	],
		// ],


		'setting_tabs' => 
		[
			'general_setting' => 'General Setting',
			'app_setting' => 'App Setting',
			'smtp_setting'  => 'SMTP Setting',
			'sms_setting'  => 'SMS Setting',
			'seo_setting'   => 'SEO Setting',
			'custom_css' => 'Custom Css',
		],
		// 'user_mannual' => [
		// 	'public_portal' => [
		// 		'video' =>'public_portal.mov',
		// 		'title_en'  => 'A Video Tutorial for Public Portal',
		// 		'title_np'  => 'पब्लिक प्रयोगकर्ताको लागि तयार पारिएको प्रयोग विधिको भिडियो'
		// 	],
		// 	'superadmin_portal' => [
		// 		'video' =>'SuperAdmin.mov',
		// 		'title_en'  => 'A Video Tutorial for SuperAdmin Portal',
		// 		'title_np'  => 'सुपर एडमिनको लागि तयार पारिएको प्रयोग विधिको भिडियो'
		// 	],
		// ],
		// 'feedbackTypes'=>[
		// 	'complaint'=>[
		// 		'name_en' =>'Complaint',
		// 		'name_np' =>'उजुरी',
		// 		'emails'   =>['complain@etc.gandaki.gov.np']
		// 	],
		// 	'question'=>[
		// 		'name_en' =>'Question',
		// 		'name_np' =>'प्रश्न',
		// 		'emails'   =>['question@etc.gandaki.gov.np']
		// 	],
		// 	'feedback'=>[
		// 		'name_en'=>'Feedback',
		// 		'name_np'=>'सुझाव',
		// 		'emails'   =>['feedback@etc.gandaki.gov.np']
		// 	],
		// 	'other'=>[
		// 		'name_en'=>'Other',
		// 		'name_np'=>'अन्य',
		// 		'emails'   =>['other@etc.gandaki.gov.np']
		// 	],
		// ],
		
		// 'academic_level' => [
		// 	'class8' => 'कक्षा ८',
		// 	'see' => 'एस एल सी / एस ई ई',
		// 	'intermediate' => '10+2',
		// 	'bachelor' => 'स्नातक',
		// 	'master' => 'स्नातकोत्तर',
		// 	'see' => 'एस एल सी / एस ई ई',
		// ],
		// 'status' => [
		// 	'new' => 'New',
		// 	'pending' => 'Pending',
		// 	'passed' => 'Passed',
		// 	'approved' => 'Approved',
		// 	'invalid' => 'Invalid',
		// 	'duplicate' => 'Duplicate',
		// 	'trash' => 'Deleted',
		// ],

		// 'application_status' => [
		// 	'passed' => 'Passed',
		// 	'approved' => 'Approved',
		// 	'pending' => 'Pending',
		// 	'invalid' => 'Invalid',
		// 	'duplicate' => 'Duplicate',
		// 	'trash' => 'Deleted',
		// ],
		
		// 'training_categories'=>[
		// 	'Plumbing' =>[
		// 		'name_en'=>'Plumbing',
		// 		'name_np'=>'प्लम्बिङ',
		// 	],
		// 	'Bag Making' =>[
		// 		'name_en'=>'Bag Making',
		// 		'name_np'=>'झोला बनाउने',
		// 	],
		// 	'Shoes Making' =>[
		// 		'name_en'=>'Plumbing',
		// 		'name_np'=>'जुत्ता बनाउने',
		// 	],
		// 	'Mini Tiller' =>[
		// 		'name_en'=>'Mini Tiller',
		// 		'name_np'=>'मिनी टिलर',
		// 	],
		// 	'Doll-Kusan Making' =>[
		// 		'name_en'=>'Doll-Kusan Making',
		// 		'name_np'=>'डल तथा कुसन बनाउने',
		// 	],
		// 	'Montessory' =>[
		// 		'name_en'=>'Montessory',
		// 		'name_np'=>'मन्टेसरी',
		// 	],
		// 	'Salon' =>[
		// 		'name_en'=>'Salon',
		// 		'name_np'=>'कपाल काट्ने (सैलुन)',
		// 	],
		// 	'Mobile Repair' =>[
		// 		'name_en'=>'Mobile Repair',
		// 		'name_np'=>'मोबाइल मर्मत',
		// 	],
		// 	'MotorCycle Repair' =>[
		// 		'name_en'=>'MotorCycle Repair',
		// 		'name_np'=>'प्लम्बिङ',
		// 	],
		// 	'Wairing' =>[
		// 		'name_en'=>'Electricity feeting and wiring',
		// 		'name_np'=>'विद्युत जडान (वायरिङ तालिम)',
		// 	],
		// 	'Computer Hardware' =>[
		// 		'name_en'=>'Computer Hardware',
		// 		'name_np'=>'कम्प्युटर हार्डवेयर',
		// 	],
		// 	'Basic Computer' =>[
		// 		'name_en'=>'Basic Computer',
		// 		'name_np'=>'आधारभूत कम्प्युटर',
		// 	],
		// 	'Parlour' =>[
		// 		'name_en'=>'Parlour',
		// 		'name_np'=>'पार्लर',
		// 	],
		// 	'Advance Parlour' =>[
		// 		'name_en'=>'Advance Parlour',
		// 		'name_np'=>'आधारभूत केश श्रृंगार',
		// 	],
		// 	'Tailoring' =>[
		// 		'name_en'=>'Tailoring',
		// 		'name_np'=>'आधारभूत कटाई सिलाई',
		// 	],
		// 	'Advance Tailoring' =>[
		// 		'name_en'=>'Advance Tailoring',
		// 		'name_np'=>'एडभान्स कटाइ सिलाइ',
		// 	],
		// 	'Fashion Design' =>[
		// 		'name_en'=>'Fashion Design',
		// 		'name_np'=>'फेसन डिजाइन',
		// 	],
		// 	'Cook' =>[
		// 		'name_en'=>'Cook',
		// 		'name_np'=>'कुक तालिम',
		// 	],
		// 	'Vegitable Farming' =>[
		// 		'name_en'=>'Vegitable Farming',
		// 		'name_np'=>'तरकारी खेती',
		// 	],
		// 	'Other' =>[
		// 		'name_en'=>'Other',
		// 		'name_np'=>'अन्य',
		// 	],
			
		// ],
	];
