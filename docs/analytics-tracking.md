# Analytics and tracking

Tracking is owned by the site plugin `wp-content/plugins/kanapka-analytics/kanapka-analytics.php`, not by a theme. Both `oxy1` and `kanapka-theme` therefore use the same account identifiers and WooCommerce remarketing payload.

## Current legacy identifiers

- Universal Analytics: `UA-58514353-1`
- Google Ads remarketing: `967972312`
- Meta Pixel: `1730975970565201`
- Yandex Metrika: `27891162`
- Google Tag Manager: not configured
- Google Analytics 4: not configured

Universal Analytics no longer processes new data. Configure GA4 or GTM when current Google Analytics reporting is required.

## Changing an account or container

Define the required constants in `wp-config.php`, above the `wp-settings.php` include:

```php
define( 'KANAPKA_GTM_ID', 'GTM-XXXXXXX' );
define( 'KANAPKA_GA4_ID', 'G-XXXXXXXXXX' );
define( 'KANAPKA_UA_ID', '' );
define( 'KANAPKA_GOOGLE_ADS_ID', '123456789' );
define( 'KANAPKA_META_PIXEL_ID', '123456789012345' );
define( 'KANAPKA_YANDEX_METRIKA_ID', '12345678' );
```

An empty value disables that integration. Constants override the plugin defaults and the `kanapka_analytics` option. Do not paste tracking scripts into theme headers, footers, product descriptions, widgets, or translation fields.

## Product remarketing data

The plugin builds `window.google_tag_params` from the current WooCommerce product or cart. Legacy copies embedded in product descriptions are removed during rendering, so the database content can be cleaned gradually without exposing JavaScript as text.

## Verification after an ID change

1. Clear page, object, CDN, and Weglot caches.
2. Open the page source and confirm each configured ID occurs once.
3. Verify `window.google_tag_params` on a product, category, cart, home, and search page.
4. Use Google Tag Assistant, Meta Pixel Helper, and the relevant provider dashboards to confirm live events.
5. Test both the original and translated Weglot URLs.

