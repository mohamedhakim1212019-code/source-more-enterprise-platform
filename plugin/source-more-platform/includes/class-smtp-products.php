<?php
if (!defined('ABSPATH')) { exit; }
final class SMTP_Products {
    const POST_TYPE = 'smt_product';
    const RFQ_TYPE = 'smt_quote_request';
    const CATEGORY = 'smt_product_category';
    const BRAND = 'smt_product_brand';

    public static function init(): void {
        add_action('init', [__CLASS__, 'register']);
        add_action('add_meta_boxes', [__CLASS__, 'meta_boxes']);
        add_action('save_post_' . self::POST_TYPE, [__CLASS__, 'save_product']);
        add_action('save_post_' . self::RFQ_TYPE, [__CLASS__, 'save_rfq']);
        add_filter('manage_' . self::POST_TYPE . '_posts_columns', [__CLASS__, 'product_columns']);
        add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [__CLASS__, 'product_column'], 10, 2);
        add_filter('manage_' . self::RFQ_TYPE . '_posts_columns', [__CLASS__, 'rfq_columns']);
        add_action('manage_' . self::RFQ_TYPE . '_posts_custom_column', [__CLASS__, 'rfq_column'], 10, 2);
        add_shortcode('source_more_quote_form', [__CLASS__, 'quote_shortcode']);
        add_shortcode('smtp_products', [__CLASS__, 'products_shortcode']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        add_action('rest_api_init', [__CLASS__, 'routes']);
    }

    public static function register(): void {
        register_taxonomy(self::CATEGORY, self::POST_TYPE, [
            'labels' => ['name' => 'Product Categories', 'singular_name' => 'Product Category'],
            'public' => true, 'show_ui' => true, 'show_in_rest' => true, 'hierarchical' => true,
            'rewrite' => ['slug' => 'product-category'],
        ]);
        register_taxonomy(self::BRAND, self::POST_TYPE, [
            'labels' => ['name' => 'Brands', 'singular_name' => 'Brand'],
            'public' => true, 'show_ui' => true, 'show_in_rest' => true, 'hierarchical' => true,
            'rewrite' => ['slug' => 'brand'],
        ]);
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name' => 'Products', 'singular_name' => 'Product', 'add_new_item' => 'Add Product',
                'edit_item' => 'Edit Product', 'all_items' => 'All Products', 'menu_name' => 'Products',
            ],
            'public' => true, 'show_ui' => true, 'show_in_menu' => 'smtp-platform', 'show_in_rest' => true,
            'supports' => ['title','editor','excerpt','thumbnail','revisions'],
            'has_archive' => 'products', 'rewrite' => ['slug' => 'products'], 'menu_icon' => 'dashicons-products',
        ]);
        register_post_type(self::RFQ_TYPE, [
            'labels' => [
                'name' => 'Quote Requests', 'singular_name' => 'Quote Request', 'all_items' => 'Quote Requests',
                'edit_item' => 'View Quote Request', 'menu_name' => 'Quote Requests',
            ],
            'public' => false, 'show_ui' => true, 'show_in_menu' => 'smtp-platform',
            'supports' => ['title','editor'], 'capability_type' => 'post', 'map_meta_cap' => true,
        ]);
    }

    public static function meta_boxes(): void {
        add_meta_box('smtp_product_details', 'Product Details', [__CLASS__, 'product_box'], self::POST_TYPE, 'normal', 'high');
        add_meta_box('smtp_rfq_details', 'Quote Request Details', [__CLASS__, 'rfq_box'], self::RFQ_TYPE, 'normal', 'high');
    }

    public static function product_box($post): void {
        wp_nonce_field('smtp_save_product', 'smtp_product_nonce');
        $fields = [
            'model' => ['Model / SKU','text'], 'product_type' => ['Product Type','select'],
            'availability' => ['Availability','select'], 'price_mode' => ['Sales Mode','select'],
            'price' => ['Indicative Price (EGP)','number'], 'brochure_url' => ['Brochure / Datasheet URL','url'],
            'key_specs' => ['Key Specifications (one per line)','textarea'], 'featured' => ['Featured Product','checkbox'],
        ];
        echo '<table class="form-table"><tbody>';
        foreach ($fields as $key => $field) {
            $value = get_post_meta($post->ID, '_smtp_' . $key, true);
            echo '<tr><th><label for="smtp_' . esc_attr($key) . '">' . esc_html($field[0]) . '</label></th><td>';
            if ($field[1] === 'textarea') {
                echo '<textarea class="large-text" rows="6" id="smtp_' . esc_attr($key) . '" name="smtp_' . esc_attr($key) . '">' . esc_textarea($value) . '</textarea>';
            } elseif ($field[1] === 'checkbox') {
                echo '<label><input type="checkbox" id="smtp_' . esc_attr($key) . '" name="smtp_' . esc_attr($key) . '" value="1" ' . checked($value, '1', false) . '> Highlight in Product Center</label>';
            } elseif ($key === 'product_type') {
                echo '<select id="smtp_product_type" name="smtp_product_type"><option value="hardware" ' . selected($value,'hardware',false) . '>Hardware</option><option value="software" ' . selected($value,'software',false) . '>Software</option><option value="service" ' . selected($value,'service',false) . '>Service / Subscription</option></select>';
            } elseif ($key === 'availability') {
                echo '<select id="smtp_availability" name="smtp_availability"><option value="available" ' . selected($value,'available',false) . '>Available</option><option value="on-request" ' . selected($value,'on-request',false) . '>Available on Request</option><option value="coming-soon" ' . selected($value,'coming-soon',false) . '>Coming Soon</option><option value="end-of-life" ' . selected($value,'end-of-life',false) . '>End of Life</option></select>';
            } elseif ($key === 'price_mode') {
                echo '<select id="smtp_price_mode" name="smtp_price_mode"><option value="quote" ' . selected($value,'quote',false) . '>Request a Quote</option><option value="direct" ' . selected($value,'direct',false) . '>Outright Sale</option><option value="subscription" ' . selected($value,'subscription',false) . '>Subscription</option></select>';
            } else {
                echo '<input class="regular-text" type="' . esc_attr($field[1]) . '" id="smtp_' . esc_attr($key) . '" name="smtp_' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
            }
            echo '</td></tr>';
        }
        echo '</tbody></table>';
    }

    public static function save_product(int $post_id): void {
        if (!isset($_POST['smtp_product_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['smtp_product_nonce'])), 'smtp_save_product')) return;
        if (!current_user_can('edit_post', $post_id) || wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;
        $map = ['model'=>'text','product_type'=>'key','availability'=>'key','price_mode'=>'key','price'=>'float','brochure_url'=>'url','key_specs'=>'textarea'];
        foreach ($map as $key => $type) {
            $raw = isset($_POST['smtp_' . $key]) ? wp_unslash($_POST['smtp_' . $key]) : '';
            if ($type === 'url') $value = esc_url_raw($raw);
            elseif ($type === 'float') $value = (string) max(0, (float) $raw);
            elseif ($type === 'textarea') $value = sanitize_textarea_field($raw);
            elseif ($type === 'key') $value = sanitize_key($raw);
            else $value = sanitize_text_field($raw);
            update_post_meta($post_id, '_smtp_' . $key, $value);
        }
        update_post_meta($post_id, '_smtp_featured', isset($_POST['smtp_featured']) ? '1' : '0');
    }

    public static function rfq_box($post): void {
        $keys = ['company'=>'Company','contact_name'=>'Contact','email'=>'Email','phone'=>'Phone','product_name'=>'Product','quantity'=>'Quantity','message'=>'Requirements','status'=>'Status','source_url'=>'Source URL'];
        echo '<table class="widefat striped"><tbody>';
        foreach ($keys as $key => $label) echo '<tr><th style="width:220px">' . esc_html($label) . '</th><td>' . nl2br(esc_html(get_post_meta($post->ID, '_smtp_' . $key, true))) . '</td></tr>';
        echo '</tbody></table>';
    }
    public static function save_rfq(int $post_id): void { /* Requests are created by REST; admin edits use core editor only. */ }

    public static function product_columns($cols): array {
        return ['cb'=>$cols['cb'],'title'=>'Product','type'=>'Type','model'=>'Model','brand'=>'Brand','availability'=>'Availability','date'=>$cols['date']];
    }
    public static function product_column($col, $id): void {
        if ($col === 'type') echo esc_html(ucfirst(get_post_meta($id,'_smtp_product_type',true) ?: 'hardware'));
        if ($col === 'model') echo esc_html(get_post_meta($id,'_smtp_model',true));
        if ($col === 'brand') { $terms = get_the_terms($id,self::BRAND); echo esc_html($terms && !is_wp_error($terms) ? implode(', ', wp_list_pluck($terms,'name')) : '—'); }
        if ($col === 'availability') echo esc_html(ucwords(str_replace('-',' ',get_post_meta($id,'_smtp_availability',true) ?: 'available')));
    }
    public static function rfq_columns($cols): array {
        return ['cb'=>$cols['cb'],'title'=>'Request','company'=>'Company','contact'=>'Contact','product'=>'Product','status'=>'Status','date'=>$cols['date']];
    }
    public static function rfq_column($col, $id): void {
        if ($col === 'company') echo esc_html(get_post_meta($id,'_smtp_company',true));
        if ($col === 'contact') echo esc_html(get_post_meta($id,'_smtp_contact_name',true)) . '<br><small>' . esc_html(get_post_meta($id,'_smtp_email',true)) . '</small>';
        if ($col === 'product') echo esc_html(get_post_meta($id,'_smtp_product_name',true));
        if ($col === 'status') echo '<strong>' . esc_html(ucfirst(get_post_meta($id,'_smtp_status',true) ?: 'new')) . '</strong>';
    }

    public static function assets(): void {
        if (is_singular(self::POST_TYPE) || is_post_type_archive(self::POST_TYPE) || is_page('products')) {
            wp_enqueue_style('smtp-platform-products', SMTP_PLATFORM_URL . 'assets/css/products.css', [], SMTP_PLATFORM_VERSION);
            wp_enqueue_script('smtp-platform-products', SMTP_PLATFORM_URL . 'assets/js/products.js', [], SMTP_PLATFORM_VERSION, true);
            wp_localize_script('smtp-platform-products', 'SMTPProducts', [
                'rest' => esc_url_raw(rest_url('source-more/v3/quote-request')), 'nonce' => wp_create_nonce('wp_rest'),
                'sending' => 'Sending your request…', 'success' => 'Thank you. Our sales team will contact you shortly.',
                'error' => 'We could not send your request. Please review the form and try again.'
            ]);
        }
    }


    public static function products_shortcode($atts = []): string {
        $atts = shortcode_atts(['limit'=>24], $atts, 'smtp_products');
        $query = new WP_Query([
            'post_type'=>self::POST_TYPE,'post_status'=>'publish','posts_per_page'=>max(1,min(100,absint($atts['limit']))),
            'orderby'=>['meta_value_num'=>'DESC','date'=>'DESC'],'meta_key'=>'_smtp_featured'
        ]);
        ob_start();
        echo '<div class="smtp-product-center">';
        if (!$query->have_posts()) {
            echo '<div class="smtp-products-empty"><h3>Products are being prepared</h3><p>Add hardware or software from Source More CRM → Products. Published products will appear here automatically.</p></div>';
        } else {
            echo '<div class="smtp-product-grid">';
            while ($query->have_posts()) { $query->the_post(); $id=get_the_ID();
                $type=get_post_meta($id,'_smtp_product_type',true) ?: 'hardware';
                $model=get_post_meta($id,'_smtp_model',true);
                $availability=get_post_meta($id,'_smtp_availability',true) ?: 'available';
                $terms=get_the_terms($id,self::BRAND); $brand=$terms && !is_wp_error($terms) ? $terms[0]->name : '';
                echo '<article class="smtp-product-card">';
                if (has_post_thumbnail()) echo '<a class="smtp-product-image" href="'.esc_url(get_permalink()).'">'.get_the_post_thumbnail($id,'medium_large').'</a>';
                echo '<div class="smtp-product-card__body"><div class="smtp-product-meta">'.esc_html(ucfirst($type));
                if ($brand) echo ' · '.esc_html($brand); echo '</div><h3><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';
                if ($model) echo '<p class="smtp-product-model">'.esc_html($model).'</p>';
                echo '<p>'.esc_html(get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()),22)).'</p>';
                echo '<div class="smtp-product-card__footer"><span>'.esc_html(ucwords(str_replace('-',' ',$availability))).'</span><a class="button" href="'.esc_url(get_permalink()).'">View Product</a></div></div></article>';
            }
            echo '</div>'; wp_reset_postdata();
        }
        echo '</div>';
        return ob_get_clean();
    }

    public static function quote_shortcode($atts = []): string {
        $atts = shortcode_atts(['product_id'=>0], $atts, 'source_more_quote_form');
        $product_id = absint($atts['product_id']);
        if (!$product_id && is_singular(self::POST_TYPE)) $product_id = get_the_ID();
        $product_name = $product_id ? get_the_title($product_id) : '';
        ob_start(); ?>
        <form class="smtp-quote-form" data-product-id="<?php echo esc_attr($product_id); ?>" novalidate>
          <div class="smtp-quote-grid">
            <label>Company name<input name="company" required autocomplete="organization"></label>
            <label>Contact person<input name="contact_name" required autocomplete="name"></label>
            <label>Business email<input name="email" type="email" required autocomplete="email"></label>
            <label>Phone / WhatsApp<input name="phone" required autocomplete="tel"></label>
            <label>Product<input name="product_name" value="<?php echo esc_attr($product_name); ?>" required></label>
            <label>Quantity<input name="quantity" type="number" min="1" value="1" required></label>
          </div>
          <label>Requirements / notes<textarea name="message" rows="4"></textarea></label>
          <label class="smtp-quote-consent"><input name="consent" type="checkbox" value="1" required> I agree that Source More Technology may contact me about this request.</label>
          <input class="smtp-honeypot" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
          <button class="btn btn-gold" type="submit">Request a Quote</button>
          <div class="smtp-quote-status" role="status" aria-live="polite"></div>
        </form>
        <?php return ob_get_clean();
    }

    public static function routes(): void {
        foreach (['source-more/v2','source-more/v3'] as $namespace) {
            register_rest_route($namespace, '/quote-request', [
                'methods' => 'POST', 'callback' => [__CLASS__, 'create_quote'], 'permission_callback' => '__return_true',
            ]);
        }
    }
    public static function create_quote(WP_REST_Request $request): WP_REST_Response|WP_Error {
        $data = $request->get_json_params();
        if (!is_array($data)) $data = $request->get_params();
        if (!empty($data['website'])) return new WP_Error('spam','Request rejected.',['status'=>400]);
        $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : 'unknown';
        if (class_exists('SMTP_Rate_Limiter') && !SMTP_Rate_Limiter::allow('rfq_' . md5($ip), 5, HOUR_IN_SECONDS)) return new WP_Error('rate_limited','Please wait before sending another request.',['status'=>429]);
        $required = ['company','contact_name','email','phone','product_name','quantity','consent'];
        foreach ($required as $key) if (empty($data[$key])) return new WP_Error('missing_field','Please complete all required fields.',['status'=>422]);
        $email = sanitize_email($data['email']);
        if (!is_email($email)) return new WP_Error('invalid_email','Please enter a valid email address.',['status'=>422]);
        $product_id = absint($data['product_id'] ?? 0);
        $product_name = sanitize_text_field($data['product_name']);
        $post_id = wp_insert_post([
            'post_type'=>self::RFQ_TYPE, 'post_status'=>'publish',
            'post_title'=>sanitize_text_field($data['company']) . ' — ' . $product_name,
            'post_content'=>sanitize_textarea_field($data['message'] ?? ''),
        ], true);
        if (is_wp_error($post_id)) return $post_id;
        $values = [
            'company'=>sanitize_text_field($data['company']), 'contact_name'=>sanitize_text_field($data['contact_name']),
            'email'=>$email, 'phone'=>sanitize_text_field($data['phone']), 'product_name'=>$product_name,
            'product_id'=>$product_id, 'quantity'=>max(1,absint($data['quantity'])),
            'message'=>sanitize_textarea_field($data['message'] ?? ''), 'status'=>'new',
            'source_url'=>esc_url_raw($data['source_url'] ?? ''), 'consent_at'=>current_time('mysql'),
        ];
        foreach ($values as $key=>$value) update_post_meta($post_id,'_smtp_'.$key,$value);
        $options = get_option('smtp_platform_options', []);
        $to = sanitize_email($options['lead_email'] ?? get_option('admin_email'));
        $subject = 'New product quote request: ' . $product_name;
        $body = "Company: {$values['company']}\nContact: {$values['contact_name']}\nEmail: {$email}\nPhone: {$values['phone']}\nProduct: {$product_name}\nQuantity: {$values['quantity']}\n\n{$values['message']}";
        wp_mail($to, $subject, $body, ['Reply-To: ' . $values['contact_name'] . ' <' . $email . '>']);
        if (class_exists('SMTP_Logger')) SMTP_Logger::info('Quote request created', ['id'=>$post_id,'product'=>$product_name]);
        return new WP_REST_Response(['success'=>true,'request_id'=>$post_id], 201);
    }
}
