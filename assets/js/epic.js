// Añade clase a body cuando se hace scroll
window.addEventListener("scroll", function() {
    if (window.scrollY > 80) {
        document.body.classList.add("scrolled");
    } else {
        document.body.classList.remove("scrolled");
    }
  });

document.addEventListener("DOMContentLoaded", function() {
    // Toggle para el menú secundario
    const toggleButton = document.querySelector(".toggle-secondary-menu");
    const navbar = document.getElementById("navbar");

    // Agrega un evento de click al toggleButton
    toggleButton.addEventListener("click", function() {
        // Alterna la clase ".secondary-menu-open" en el navbar
        navbar.classList.toggle("secondary-menu-open");
    });

    // Agregar funcionalidad para el mega menú
    const megaMenuGrid = document.querySelector(".mega-menu-grid");
    const megaIndicator = document.querySelector(".mega-indicator");
    const masthead = document.getElementById("masthead");

    megaMenuGrid.addEventListener("click", function(event) {
        event.stopPropagation(); // Evita que el click se propague al documento
        masthead.classList.add("main-mega-menu-open");
    });

    megaIndicator.addEventListener("click", function(event) {
        event.stopPropagation(); // Evita que el click se propague al documento
        masthead.classList.add("main-mega-menu-open");
    });

    document.addEventListener("click", function(event) {
        if (!masthead.contains(event.target) && !event.target.closest(".mega-menu-grid .mega-menu-link")) {
            masthead.classList.remove("main-mega-menu-open");
        }
    });

    masthead.addEventListener("click", function(event) {
        if (!event.target.closest(".mega-menu-grid .mega-menu-link") && !event.target.classList.contains("mega-menu-link")) {
            masthead.classList.remove("main-mega-menu-open");
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el carousel
    var $carousel = jQuery('.wp-block-cb-carousel');

    // Selecciona los botones
    var prevButton = document.querySelector('.btn-prev-slide');
    var nextButton = document.querySelector('.btn-next-slide');

    // Función para actualizar el estado de los botones
    function updateButtons(currentSlide, slideCount) {
        if (currentSlide === 0) {
            prevButton.disabled = true;
        } else {
            prevButton.disabled = false;
        }

        if (currentSlide === slideCount - 1) {
            nextButton.disabled = true;
        } else {
            nextButton.disabled = false;
        }
    }

    // Asegúrate de que los botones existen
    if (prevButton && nextButton) {
        // Asegúrate de que el carousel esté inicializado
        if ($carousel.hasClass('slick-initialized')) {
            // Añade eventos a los botones
            prevButton.addEventListener('click', function() {
                $carousel.slick('slickPrev');
            });

            nextButton.addEventListener('click', function() {
                $carousel.slick('slickNext');
            });

            // Actualiza el estado de los botones al cargar
            updateButtons($carousel.slick('slickCurrentSlide'), $carousel.slick('getSlick').slideCount);

            // Actualiza el estado de los botones después de cambiar de slide
            $carousel.on('afterChange', function(event, slick, currentSlide) {
                updateButtons(currentSlide, slick.slideCount);
            });
        } else {
            // Si el carousel no está inicializado, espera a que se inicialice
            $carousel.on('init', function(event, slick) {
                // Añade eventos a los botones
                prevButton.addEventListener('click', function() {
                    $carousel.slick('slickPrev');
                });

                nextButton.addEventListener('click', function() {
                    $carousel.slick('slickNext');
                });

                // Actualiza el estado de los botones al cargar
                updateButtons(slick.currentSlide, slick.slideCount);
            });

            // Actualiza el estado de los botones después de cambiar de slide
            $carousel.on('afterChange', function(event, slick, currentSlide) {
                updateButtons(currentSlide, slick.slideCount);
            });
        }
    } else {
        console.error('Los botones .btn-prev-slide y/o .btn-next-slide no se encontraron en el DOM.');
    }
});