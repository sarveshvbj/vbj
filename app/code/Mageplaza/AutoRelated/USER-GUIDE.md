## Documentation

- Installation guide: https://www.mageplaza.com/install-magento-2-extension/
- User Guide: https://docs.mageplaza.com/automatic-related-products/index.html
- Product page: https://www.mageplaza.com/magento-2-automatic-related-products/
- FAQs: https://www.mageplaza.com/faqs/
- Get Support: https://mageplaza.freshdesk.com/ or support@mageplaza.com
- Changelog: https://www.mageplaza.com/changelog/m2-automatic-related-products.txt
- License agreement: https://www.mageplaza.com/LICENSE.txt



## How to install

### Install ready-to-paste package (Recommended)

- Installation guide: https://docs.mageplaza.com/kb/installation.html



## How to upgrade

1. Backup

Backup your Magento code, database before upgrading.

2. Remove AutoRelated folder 

In case of customization, you should backup the customized files and modify in newer version. 
Now you remove `app/code/Mageplaza/AutoRelated` folder. In this step, you can copy override AutoRelated folder but this may cause of compilation issue. That why you should remove it.

3. Upload new version
Upload this package to Magento root directory

4. Run command line:

```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```


## FAQs


#### Q: I got error: `Mageplaza_Core has been already defined`
A: Read solution: https://github.com/mageplaza/module-core/issues/3


#### Q: My site is down
A: Please follow this guide: https://www.mageplaza.com/blog/magento-site-down.html




## Support

- FAQs: https://www.mageplaza.com/faqs/
- https://mageplaza.freshdesk.com/
- support@mageplaza.com