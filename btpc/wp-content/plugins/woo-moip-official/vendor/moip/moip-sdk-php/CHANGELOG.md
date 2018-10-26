# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

<a name="v1.3.2"></a>
### [1.3.2](https://github.com/moip/moip-sdk-php/compare/v1.3.0...v1.3.2) (2017-09-18)


#### Bug Fixes
- **MoipResource:** Initializing variable to prevent E_NOTICE ([42ee471](https://github.com/moip/moip-sdk-php/commit/42ee471ce2b2131cb326f434fd2a105ceb7f1f45))
- **Connect:** Removing type declaration from methods to compatibility with PHP older versions ([77abe58](https://github.com/moip/moip-sdk-php/commit/77abe58da9e5b658160f1a279ba6227e9ade4409))

<a name="v1.3.1"></a>
### [1.3.1](https://github.com/moip/moip-sdk-php/compare/v1.3.0...v1.3.1) (2017-08-11)


#### Bug Fixes

* **Account:**  Fix account create without company set (#157) ([4d7f4bc5](4d7f4bc5))

<a name="1.3.0"></a>
# [1.3.0](https://github.com/moip/moip-sdk-php/compare/v1.2.0...v1.3.0) (2017-08-08)

## Bug Fixes
- **order:**
  - fix adding of installments in checkout preferences
  ([3dee9fa](https://github.com/moip/moip-sdk-php/commit/3dee9fa7b9a5863ba4828de2f03a5fd7a1254898))
- **refund:**
  - fix of bank account refund
  ([d336f9f](https://github.com/moip/moip-sdk-php/commit/d336f9f04dc92a978e3d67942091b573c9a30643))
- fix method to return HATEOAS links from API
  ([025bfde](https://github.com/moip/moip-sdk-php/commit/025bfdedde5bfe953264b24daa0ba371e73e43cd))
- fix method to get DateTime from resources
  ([3d30cbb](https://github.com/moip/moip-sdk-php/pull/152/commits/3d30cbbf49fb9c4ee1b6049dd93cd3487a9fef81))


## New Features
- **escrow:** add escrow resource
  ([ed99701](https://github.com/moip/moip-sdk-php/commit/ed9970156de1dea88a091fd33b54bcec8f91ce92)),
- **notification preferences:** add notification preferences resource
  ([e553d8b](https://github.com/moip/moip-sdk-php/commit/e553d8b9c9878009cb2d2e021043f3ebbaeb2dc5))
- **customer credit card:** add resource to add more credit cards to customer
  ([d327f03](https://github.com/moip/moip-sdk-php/commit/d327f03b5d2449dbac95f3f3cabcd17a19b8853a))

## BREAKING CHANGES
Now tests are run using OAuth authentication instead Basic Auth, because now there are tests to resources that only uses OAuth authentication.