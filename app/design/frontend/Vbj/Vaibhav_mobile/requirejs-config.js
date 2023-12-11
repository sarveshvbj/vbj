var config = {
    /*map: {
        '*': {
            'owlcarousel':'js/owl.carousel.min'
        }
    },*/
    paths: {
            'bootstrap':'js/bootstrap.bundle.min',
            'owlcarousel':'js/owl.carousel.min'
    } ,
    /*shim: {
        'bootstrap': {
            'deps': ['jquery']
        }
    }*/
    "shim": {
      "js/bootstrap.bundle.min": ["jquery"],
      "js/owl.carousel.min": ["jquery"]                
      },

    deps: [
        "js/v-custom"
    ]
};