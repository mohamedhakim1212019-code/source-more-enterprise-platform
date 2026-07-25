# Source More Platform v3.6.1

## Contact Form CRM Integration

This maintenance release connects the existing enterprise theme **Request a Consultation** form to the SMEP CRM without changing the theme, form markup, JavaScript, AJAX action, or nonce.

### What happens after a successful submission

- A CRM lead is created in **Source More CRM → All Leads**.
- Lead source is saved as **Website Contact**.
- Initial CRM status is **New**.
- Full name, company, email, phone, area of interest, inquiry message, source URL, consent timestamp, and activity history are stored.
- An internal notification is sent to the configured **Lead notification email**.
- A confirmation email is sent to the customer.
- Email failures are logged in Diagnostics and do not discard a successfully saved lead.

### Compatibility

- Plugin version: 3.6.1
- Database version: 3.1.0
- Theme changes: none required
- Existing Fleet, Products, Quote Requests, CRM, and AI Assistant behavior remains unchanged.

### LocalWP test

1. Submit the Contact page form.
2. Confirm the success message appears.
3. Open **Source More CRM → All Leads** and verify a new Website Contact lead.
4. Open the lead and verify Area of Interest, Inquiry Message, Consent Timestamp, and Activity History.
5. Open Mailpit and verify the admin notification and customer confirmation.
6. Filter All Leads by **Website Contact**.
