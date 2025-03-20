<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instituto Vilcanota - Inicio</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/slider.css">
    <style>
        .career-item {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin: 2rem 0;
            padding: 1rem;
        }
        .career-item.reverse {
            flex-direction: row-reverse;
        }
        .career-content {
            flex: 1;
        }
        .career-image {
            flex: 1;
            max-width: 500px;
        }
        .career-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        include 'items/navbar.php';
    } else {
        // Determinar qué navbar incluir
        if ($_SESSION['user_id'] >= 1 && $_SESSION['user_id'] <= 3) {
            include 'items/navbar_admin.php';
        } else { 
            include 'items/navbar_user.php';
        }
    }
    ?>

    <div class="main-content">
        <h1>Bienvenidos al Instituto de Educación Superior Tecnológico Público Vilcanota</h1>
    </div> 
        <!-- Slider Section -->
        <div class="slider-container">
        <div class="slider">
            <div class="slide">
                <img src="img/IMG15.jpg" alt="Instituto Vilcanota">
                <div class="caption">Preparándonos para el futuro</div>
            </div>
            <div class="slide">
                <img src="img/IMG26.jpg" alt="Instituto Vilcanota">
                <div class="caption">Excelencia Académica</div>
            </div>
            <div class="slide">
                <img src="img/IMG29.jpg" alt="Instituto Vilcanota">
                <div class="caption">Liderazgo y Desarrollo</div>
            </div>
        </div>
        <div class="slider-nav">
            <button class="prev"><i class="fas fa-chevron-left"></i></button>
            <button class="next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </div>   
    <div class="main-content">
        <section class="institute-intro">
            <h2>Formando Profesionales del Futuro</h2>
            <p>El Instituto Vilcanota es una institución educativa líder en la región, comprometida con la excelencia académica y el desarrollo profesional de nuestros estudiantes. Con más de años de experiencia, nos dedicamos a formar profesionales competentes y preparados para los desafíos del mundo moderno.</p>
        </section>

        <section class="careers">
            <h2>Nuestras Carreras Profesionales</h2>
            
            <div class="career-item">
                <div class="career-content">
                    <h3>Desarrollo de Sistemas de Información</h3>
                    <p>Forma profesionales capaces de diseñar, implementar y mantener soluciones tecnológicas innovadoras. Nuestros estudiantes aprenden las últimas tecnologías y metodologías en desarrollo de software, preparándose para liderar la transformación digital.</p>
                </div>
                <div class="career-image">
                    <img src="img/IMG20.jpg" alt="Desarrollo de Sistemas">
                </div>
            </div>

            <div class="career-item reverse">
                <div class="career-content">
                    <h3>Contabilidad</h3>
                    <p>Prepara expertos en gestión contable y financiera, con sólidos conocimientos en normativas tributarias y sistemas contables modernos. Nuestros graduados están capacitados para administrar recursos financieros en cualquier tipo de organización.</p>
                </div>
                <div class="career-image">
                    <img src="img/IMG1.jpg" alt="Contabilidad">
                </div>
            </div>

            <div class="career-item">
                <div class="career-content">
                    <h3>Producción Agropecuaria</h3>
                    <p>Forma profesionales especializados en técnicas modernas de producción agrícola y ganadera, con enfoque en sostenibilidad y tecnologías innovadoras para el sector agrario. Preparamos líderes para la transformación del sector agropecuario.</p>
                </div>
                <div class="career-image">
                    <img src="img/IMG6.jpg" alt="Producción Agropecuaria">
                </div>
            </div>

            <div class="career-item reverse">
                <div class="career-content">
                    <h3>Enfermería Técnica</h3>
                    <p>Desarrolla profesionales de la salud con alto sentido humanístico y excelencia técnica. Nuestros estudiantes reciben formación integral en cuidados de salud, preparándose para brindar atención de calidad en diversos contextos sanitarios.</p>
                </div>
                <div class="career-image">
                    <img src="img/IMG12.jpg" alt="Enfermería Técnica">
                </div>
            </div>

            <div class="career-item">
                <div class="career-content">
                    <h3>Construcción Civil</h3>
                    <p>Forma expertos en técnicas modernas de construcción y gestión de proyectos de infraestructura. Los estudiantes adquieren competencias en diseño, supervisión y ejecución de obras civiles, con énfasis en sostenibilidad y seguridad.</p>
                </div>
                <div class="career-image">
                    <img src="img/IMG0.jpg" alt="Construcción Civil">
                </div>
            </div>
        </section>
    </div>

    <script>
        const slides = document.querySelectorAll('.slide');
        const prevButton = document.querySelector('.prev');
        const nextButton = document.querySelector('.next');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        nextButton.addEventListener('click', nextSlide);
        prevButton.addEventListener('click', prevSlide);

        showSlide(currentSlide);

        // Auto slide
        setInterval(nextSlide, 5000); // Change slide every 5 seconds
    </script>
</body>
</html>
