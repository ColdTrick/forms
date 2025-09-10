# Forms

![Elgg 6.3](https://img.shields.io/badge/Elgg-6.3-green.svg)
![Lint Checks](https://github.com/ColdTrick/entity_view_counter/actions/workflows/lint.yml/badge.svg?event=push)
[![Latest Stable Version](https://poser.pugx.org/coldtrick/forms/v/stable.svg)](https://packagist.org/packages/coldtrick/forms)
[![License](https://poser.pugx.org/coldtrick/forms/license.svg)](https://packagist.org/packages/coldtrick/forms)

A form builder for Elgg

## Manage forms

- From the admin backend go to Utilities -> Manage forms
- or go to ``/forms``

## Create a new form

- As a Site Administrator
- From the form overview page (see above) click 'Create form'
- Fill in a Title
- Fill in a URL to use for the form (the form will be available under ``/forms/<your entry>``)
- Most of the rest of the form is optional but will give more information to the users
- Choose an Endpoint for the form results
  - this can be a CSV-file where a Site Administrator will have to download the results
  - or an e-mail endpoint
- Save the form (it's not yet ready for use)

### E-mail endpoint options

- CC and BCC field configuration
- Uploaded files as attachment
- E-mail fields set as an additional To, CC or BCC

## Compose a form

- As a Site Administrator
- From the form overview page (see above)
- In the Entity menu (three dots) click Compose
- Here you can
  - Add pages to the form
  - Add sections to a page
  - Add fields to a section
- To add fields to a section, drag them from the sidebar
- To edit a field, hover over the field and click the Edit icon (don't forget to Save changes to a field)
- Here you can change the label, add a help text, mark a field as required, etc.
- Pages, Sections and Fields can be reordered using drag-and-drop
- Once done Save the form
- Users can now use your form

## Custom validation rules

- As a Site Administrator
- Go to ``/forms/validation_rules``
- Click Add
- Here you can give it a Label
- Provide a regex for the field validation
  - For example, a Dutch postal code looks like ``1234AB`` or ``5678 CD``
  - So the regex would be ``^[0-9]{4} ?[a-zA-Z]{2}$``
- Optional custom error message
- Save
- The validation rule can now be applied to a field on a form
