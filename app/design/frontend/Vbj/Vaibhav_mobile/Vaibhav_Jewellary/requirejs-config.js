var config = {

    paths:  {
        "bootstrap" : "js/bootstrap.bundle.min",
        "owlCarousel": "js/owl.carousel.min"
    },

    "shim": {
      "js/bootstrap.bundle.min": ["jquery"],
      "js/owl.carousel.min": ["jquery"]                
      },

    deps: [
        "js/v-custom"
    ]
};