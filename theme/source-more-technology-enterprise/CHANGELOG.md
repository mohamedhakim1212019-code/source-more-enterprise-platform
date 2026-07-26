## 7.7.0 — Bilingual Products & Platform UI
- Added a complete English/Arabic Product Center archive experience.
- Localized Product filters, types, availability, empty states, pagination, and calls to action.
- Added bilingual product detail labels, pricing language, specifications, datasheet actions, and quotation section.
- Added language-aware Product Center URLs for Polylang archive routes.
- Connected Arabic Product pages to the bilingual Platform quote form and customer experience.
- Refined Arabic Fleet Calculator company naming and retained all v7.6.3 navigation, Contact, and bilingual page fixes.
- Updated theme assets and version metadata to 7.7.0.

## 7.6.3 — Arabic Contact Page
- Added Contact Us / تواصل معنا to the bilingual page provisioning catalogue.
- Creates or repairs the Arabic Contact translation and links it to the English Contact page in Polylang.
- Assigns the dedicated Contact page template to both languages.
- Reuses an existing unlinked Arabic Contact page when present to avoid duplicates.
- Ensures the Arabic header Contact button and all consultation CTAs open the Arabic Contact page directly.

# Changelog

## 7.6.2
- Fixed Arabic Mega Menu links for hierarchical IT Consulting and Digital Transformation pages.
- Added a robust language-aware page resolver that locates pages by slug regardless of parent path.
- Prioritized the English source page that has a valid Polylang translation relationship.
- Kept fallback links inside the active language.
- Prevented the bilingual repair utility from creating duplicate pages after pages are assigned to a parent.
- Updated active-menu detection for hierarchical bilingual pages.

## 7.6.1
- Added complete bilingual IT Consulting and Digital Transformation solution pages.
- Changed mega-menu links from inactive anchors to the new real pages.
- Extended Bilingual Pages setup to create, translate, link, and parent the two pages.
- Removed the small eyebrow line from all internal page heroes for a cleaner visual hierarchy.
- Ensured textual company-name references use “سورس مور تكنولوجي” on Arabic pages while preserving the English logo lockup.
- Updated theme asset cache versions.

## 7.6.0
- Added complete bilingual Industries and Resources experiences.
- Added eight sector-specific English and Arabic landing pages.
- Added Company Profile, Technology Assessment, FAQs & Support, Insights, and Downloads pages.
- Connected the Resources Mega Menu to real bilingual pages.
- Added non-destructive Appearance > Bilingual Pages setup and repair utility.
- Added responsive and RTL visual systems for the new pages.
- Kept the Platform plugin and existing content unchanged.

## 7.5.1
- Stabilized desktop mega-menu hover interaction.
- Added a guarded pointer-leave delay and transparent hover bridge.
- Removed the need to click submenu arrows on desktop.
- Preserved mobile accordion and keyboard behavior.


## 7.5.0
- Added bilingual mega navigation for Solutions, Industries, and Resources.
- Added responsive mobile accordion behavior through the existing accessible submenu controls.
- Made corporate About, Solutions, and solution-detail templates visually authoritative while preserving stored editor content.
- Updated Arabic corporate naming to سورس مور تكنولوجي in key theme experiences.
- Retained all v7.4.1 footer, process, RTL, and sticky-header fixes.

## 7.4.1 — Arabic Footer & Solution Process Polish
- Fixed international phone-number direction in RTL footer layouts.
- Kept the footer logo lockup visually consistent in English and Arabic.
- Added the Arabic company name “سورس مور تكنولوجي” to the Arabic footer and copyright line.
- Improved Arabic WhatsApp greeting copy.
- Restyled solution delivery steps as high-contrast white cards on a soft background.
- Added dark navy headings, readable secondary text, borders, shadows, and hover feedback.

## 7.4.0 — Bilingual About & Solutions
- Added complete English and Arabic marketing copy for the About Us page.
- Added complete English and Arabic copy for the Solutions hub.
- Added bilingual content for all eight individual solution pages.
- Localized headings, CTAs, challenges, capabilities, outcomes, delivery stages, and sector labels.
- Added Arabic RTL typography refinements for About and Solutions pages.
- Preserved optional Gutenberg override: editor content replaces the built-in page template when provided.
- Kept all stable URLs, Polylang links, homepage manager, sticky header, logo behavior, and SMEP integration unchanged.

## 7.3.5
- Added a dedicated full-width bilingual Homepage Content Manager under Appearance.
- Removed dependency on unreliable Gutenberg legacy meta-box rendering for homepage copy editing.
- Added English/Arabic language tabs linked to the corresponding Polylang homepage.
- Added secure per-language saving and front-end preview links.
- Reduced Arabic hero headline size for a cleaner executive layout.

## 7.3.4
- Restored a reliable editable Homepage Showcase Content meta box below Gutenberg.
- Removed dependency on the unstable Gutenberg document-panel integration.
- Kept independent English and Arabic homepage values and defaults.
- Reduced Arabic hero headline sizing across desktop, tablet, and mobile.

## 7.3.3
- Fixed non-editable Homepage Showcase Content controls in Gutenberg.
- Added native bilingual homepage editor panel backed by registered post meta.
- Added Arabic editor labels and visible default copy.
- Preserved independent English and Arabic homepage content.


## 7.3.2
- Added per-language editable homepage showcase fields for Polylang front-page translations.
- Rewrote Arabic homepage defaults with a stronger Egyptian-market B2B marketing tone.
- Improved Arabic hero typography and RTL floating action placement.
- Preserved stable sticky header and identical brand-lockup composition across languages.

## 7.3.1 — Header Stability Hotfix
- Kept the sticky header below the WordPress admin bar while logged in.
- Prevented the logo from being clipped during scrolling.
- Kept the complete logo lockup visually identical in English and Arabic.
- Corrected mobile navigation and backdrop offsets when the admin bar is visible.
- Corrected mega-menu and anchor offsets for the sticky header.

## 7.3.0 — Website Showcase Sprint A
- Added premium bilingual partner-ready homepage.
- Added technology coverage and ecosystem sections.
- Added smart customer experience section integrated with SMEP tools.
- Added non-destructive homepage layout switch.
- Added new hero SVG and expanded responsive/RTL styling.


## 6.0.0
- Added secure native contact workflow.
- Added eight industry landing pages.
- Added 404, search, archive, single post, and empty-state templates.
- Added breadcrumbs and baseline Organization schema.
- Added Insights page and production settings.
- Updated all asset and installer version identifiers.

# Source More Technology Theme

## 5.0.0-beta.1 — Homepage conversion foundation

### Added
- Customer-focused Business Challenges section with links to relevant solutions.
- Dedicated “Ask Source More AI” homepage section and assistant-launch bridge.
- Stronger focus states, reduced-motion support, and Escape-key mobile-menu closing.
- Hover and interaction refinements for cards and industries.

### Changed
- Homepage journey reordered around problem, solution, proof, tools, and action.
- Hero messaging repositioned Source More as one integrated technology partner.
- Primary hero CTA changed to a free consultation.
- Theme asset version updated for cache busting.

### Preserved
- Existing WordPress page URLs and bilingual helpers.
- Fleet Savings Calculator and floating calculator shortcut.
- WhatsApp integration.
- `wp_head()`, `wp_body_open()`, and `wp_footer()` hooks required by the Source More Platform plugin.


## 6.1.0
- Integrated with Source More Platform v2.1.0.
- Added companion-plugin detection and admin guidance.
- Updated fleet calculator compatibility messaging.
- Bumped asset versions for deployment cache invalidation.

## 6.2.0
- Added Product Center archive and product detail templates.
- Added filtering by product type and brand.
- Added integrated request-a-quote experience powered by Platform Plugin 2.2.
- Repaired hardcoded `/services/` links to use the published `/solutions/` page.
- Added Products to the fallback navigation and starter-site installer.

## 7.1.0
- Added non-destructive Gutenberg content migration for Home, About, Solutions, Products, Industries, and Resources.
- Added Appearance > Content Setup recovery tool.
- Added editor styles matching the front-end design.
- Updated asset cache versions.
