<?php
/**
 * AI Assistant knowledge retrieval and deterministic fallback answers.
 *
 * @package SourceMorePlatform
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class SMTP_Assistant_Knowledge {
	/** @return array{reply:string,source:string,action:string,confidence:float} */
	public function answer( string $question, string $language = 'en' ): array {
		$lang     = SMTP_I18n::language( $language );
		$question = trim( wp_strip_all_tags( $question ) );
		$entries  = array_merge( $this->custom_entries( $lang ), $this->built_in_entries( $lang ) );
		$best     = null;
		$score    = 0.0;

		foreach ( $entries as $entry ) {
			$current = $this->score( $question, $entry );
			if ( $current > $score ) { $score = $current; $best = $entry; }
		}

		if ( null === $best || $score < 1.0 ) {
			return array(
				'reply'      => SMTP_I18n::text(
					'Source More provides Managed Print Services, enterprise printing, document management, IT infrastructure, cloud, cybersecurity, office automation, maintenance contracts, and business technology consulting.',
					'تقدم سورس مور حلول الطباعة المُدارة والطباعة المؤسسية وإدارة المستندات والبنية التحتية والسحابة والأمن السيبراني وأتمتة بيئة العمل وعقود الصيانة والاستشارات التقنية.',
					$lang
				),
				'source'     => 'built-in-general-' . $lang,
				'action'     => '',
				'confidence' => 0.35,
			);
		}

		return array( 'reply'=>(string)$best['reply'], 'source'=>(string)$best['source'], 'action'=>sanitize_key($best['action']??''), 'confidence'=>min(1.0,$score/12) );
	}

	/** @return array<int,array<string,mixed>> */
	private function custom_entries( string $lang ): array {
		$args = array(
			'post_type'      => SMTP_Assistant_Content_Types::KNOWLEDGE_POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => 100,
			'orderby'        => array( 'menu_order'=>'ASC', 'date'=>'DESC' ),
			'no_found_rows'  => true,
			'meta_query'     => array( 'relation'=>'OR', array('key'=>'_smtp_ai_enabled','value'=>'1'), array('key'=>'_smtp_ai_enabled','compare'=>'NOT EXISTS') ),
		);
		if ( function_exists( 'pll_current_language' ) ) $args['lang'] = $lang;
		$posts = get_posts( $args );
		$entries = array();
		foreach ( $posts as $post ) {
			$reply = trim( wp_strip_all_tags( $post->post_content ) ); if ( '' === $reply ) continue;
			$keywords = (string)get_post_meta($post->ID,'_smtp_ai_keywords',true);
			$entries[] = array( 'title'=>(string)$post->post_title, 'keywords'=>$this->keyword_list($keywords.','.$post->post_excerpt), 'reply'=>$reply, 'action'=>sanitize_key((string)get_post_meta($post->ID,'_smtp_ai_action',true)), 'priority'=>max(1,min(10,absint(get_post_meta($post->ID,'_smtp_ai_priority',true)?:5))), 'source'=>'knowledge-'.$post->ID );
		}
		return $entries;
	}

	/** @return array<int,array<string,mixed>> */
	private function built_in_entries( string $lang ): array {
		if ( 'ar' === $lang ) {
			$entries = array(
				array('title'=>'خدمات الطباعة المدارة','keywords'=>array('mps','الطباعة المدارة','الطباعة المُدارة','ادارة الطباعة','إدارة الطباعة','اسطول الطابعات','تكلفة الصفحة'),'reply'=>'تجمع خدمات الطباعة المُدارة بين تقييم الأسطول وتحسين توزيع الأجهزة وتوفير المستلزمات والصيانة والتقارير وضوابط الأمان وإدارة التكلفة بنظام واضح ويمكن التنبؤ به.','action'=>'','priority'=>6,'source'=>'built-in-mps-ar'),
				array('title'=>'حاسبة وفر الطباعة','keywords'=>array('احسب','حاسبة','توفير','خفض التكلفة','تكلفة الطباعة','تقييم الاسطول','تقييم الأسطول'),'reply'=>'استخدم حاسبة وفر الطباعة للحصول على تقدير فوري يعتمد على عدد الأجهزة وأحجام الطباعة وتكلفة الصفحة ومصروفات الخدمة.','action'=>'calculator','priority'=>8,'source'=>'built-in-savings-ar'),
				array('title'=>'حلول السحابة ومايكروسوفت','keywords'=>array('السحابة','مايكروسوفت','microsoft 365','azure','teams','sharepoint','الهوية الرقمية'),'reply'=>'تدعم سورس مور حلول مايكروسوفت والسحابة لتعزيز التعاون وإدارة الهوية واستمرارية الأعمال والوصول الآمن وزيادة إنتاجية بيئة العمل الحديثة.','action'=>'','priority'=>5,'source'=>'built-in-cloud-ar'),
				array('title'=>'الأمن السيبراني','keywords'=>array('الامن','الأمن','امن سيبراني','أمن سيبراني','cybersecurity','حماية الشبكات','حماية الاجهزة','أمان الطباعة'),'reply'=>'يشمل نهجنا للأمن السيبراني تقييم المخاطر وحماية الشبكات والأجهزة الطرفية والتحكم في الوصول وأمان الطباعة والمراقبة والسياسات العملية للحوكمة.','action'=>'','priority'=>5,'source'=>'built-in-security-ar'),
				array('title'=>'إدارة المستندات','keywords'=>array('ادارة المستندات','إدارة المستندات','سير العمل','الرقمنة','المسح الضوئي','الارشفة','الأرشفة','بدون ورق','الأتمتة'),'reply'=>'تنظم حلول إدارة المستندات عمليات الالتقاط والفهرسة والتخزين الآمن والبحث والموافقات وسياسات الاحتفاظ والتكامل مع أنظمة الأعمال.','action'=>'','priority'=>5,'source'=>'built-in-documents-ar'),
				array('title'=>'المنتجات وعروض الأسعار','keywords'=>array('منتج','طابعة','ماكينة تصوير','mfp','عرض سعر','سعر','شراء','منتجات'),'reply'=>'يمكنك تصفح مركز المنتجات للتعرف على الأجهزة والحلول المتاحة، ثم إرسال طلب عرض سعر من صفحة المنتج للحصول على السعر والتوافر والمواصفات التجارية.','action'=>'products','priority'=>6,'source'=>'built-in-products-ar'),
				array('title'=>'التواصل والتقييم','keywords'=>array('احجز','تواصل','اتصل بي','المبيعات','تقييم','موعد','عرض توضيحي','أتحدث مع شخص','استشارة'),'reply'=>'يمكنني إرسال بياناتك إلى فريق سورس مور لترتيب تقييم احترافي أو مناقشة تجارية تناسب احتياجات مؤسستك.','action'=>'lead_capture','priority'=>9,'source'=>'built-in-contact-ar'),
			);
		} else {
			$entries = array(
				array('title'=>'Managed Print Services','keywords'=>array('mps','managed print','print management','printer fleet','cost per page'),'reply'=>'Managed Print Services combines fleet assessment, device optimization, supplies, maintenance, reporting, security controls, and predictable cost-per-page management.','action'=>'','priority'=>6,'source'=>'built-in-mps'),
				array('title'=>'Fleet Savings','keywords'=>array('calculate','calculator','saving','savings','reduce cost','print cost','fleet assessment'),'reply'=>'Use the Fleet Savings Calculator for an immediate estimate based on devices, monthly volumes, page costs, and service expenses.','action'=>'calculator','priority'=>8,'source'=>'built-in-savings'),
				array('title'=>'Cloud and Microsoft Solutions','keywords'=>array('cloud','microsoft','microsoft 365','azure','teams','sharepoint','identity'),'reply'=>'Source More supports Microsoft and cloud solutions for collaboration, identity, business continuity, secure access, and modern workplace productivity.','action'=>'','priority'=>5,'source'=>'built-in-cloud'),
				array('title'=>'Cybersecurity','keywords'=>array('security','cybersecurity','cyber','endpoint protection','network security','print security'),'reply'=>'Our cybersecurity approach covers risk assessment, network and endpoint protection, secure access, print security, monitoring, and practical governance policies.','action'=>'','priority'=>5,'source'=>'built-in-security'),
				array('title'=>'Document Management','keywords'=>array('document management','workflow','digitization','scan','archive','paperless','automation'),'reply'=>'Document management solutions organize capture, indexing, secure storage, search, approval workflows, retention, and integration with business systems.','action'=>'','priority'=>5,'source'=>'built-in-documents'),
				array('title'=>'Products and Quotations','keywords'=>array('product','printer','multifunction','mfp','copier','quote','quotation','price','buy'),'reply'=>'Browse the Product Center for available devices and solutions. Product pages include a Request a Quote form for commercial pricing and availability.','action'=>'products','priority'=>6,'source'=>'built-in-products'),
				array('title'=>'Contact and Assessment','keywords'=>array('book','contact','call me','sales','assessment','appointment','demo','talk to someone'),'reply'=>'I can send your details to the Source More team for a professional assessment or commercial discussion.','action'=>'lead_capture','priority'=>9,'source'=>'built-in-contact'),
			);
		}
		return apply_filters( 'smtp_assistant_builtin_knowledge', $entries, $lang );
	}

	/** @param array<string,mixed> $entry */
	private function score( string $question, array $entry ): float {
		$normalized=$this->normalize($question); $title=$this->normalize((string)($entry['title']??'')); $priority=max(1,min(10,absint($entry['priority']??5))); $score=0.0;
		if ( ''!==$title && str_contains($normalized,$title) ) $score+=7;
		foreach ((array)($entry['keywords']??array()) as $keyword) { $keyword=$this->normalize((string)$keyword); if(''===$keyword)continue; if(str_contains($normalized,$keyword))$score+=str_contains($keyword,' ')?4:2; }
		$question_tokens=array_unique(array_filter(preg_split('/[^\p{L}\p{N}]+/u',$normalized)?:array()));
		$entry_tokens=array_unique(array_filter(preg_split('/[^\p{L}\p{N}]+/u',$title.' '.implode(' ',(array)($entry['keywords']??array())))?:array()));
		$score+=min(3,count(array_intersect($question_tokens,$entry_tokens))*0.5);
		return $score*(0.75+($priority/20));
	}
	/** @return array<int,string> */
	private function keyword_list( string $raw ): array { $parts=preg_split('/[,\n\r;]+/',$raw)?:array(); return array_values(array_filter(array_map('trim',$parts))); }
	private function normalize( string $text ): string { $text=remove_accents(wp_strip_all_tags($text)); return function_exists('mb_strtolower')?mb_strtolower($text):strtolower($text); }
}
