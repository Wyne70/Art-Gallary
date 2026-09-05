/* =====================================================
   ERWYNE ARTSPACE
   FUTURISTIC JAVASCRIPT
   VANILLA JAVASCRIPT ONLY
===================================================== */


/* =====================================================
   DOM READY
===================================================== */

document.addEventListener("DOMContentLoaded", function () {


    /* =================================================
       MOBILE NAVIGATION
    ================================================= */

    const menuToggle =
        document.getElementById("menuToggle");

    const navLinks =
        document.getElementById("navLinks");


    if (menuToggle && navLinks) {

        menuToggle.addEventListener(
            "click",
            function () {

                const isOpen =
                    navLinks.classList.toggle("open");

                menuToggle.classList.toggle(
                    "active",
                    isOpen
                );

                menuToggle.setAttribute(
                    "aria-expanded",
                    String(isOpen)
                );

            }
        );


        /* Close menu after clicking a link */

        const navigationLinks =
            navLinks.querySelectorAll("a");


        navigationLinks.forEach(
            function (link) {

                link.addEventListener(
                    "click",
                    function () {

                        navLinks.classList.remove(
                            "open"
                        );

                        menuToggle.classList.remove(
                            "active"
                        );

                        menuToggle.setAttribute(
                            "aria-expanded",
                            "false"
                        );

                    }
                );

            }
        );


        /* Close menu when clicking outside */

        document.addEventListener(
            "click",
            function (event) {

                if (
                    !navLinks.contains(event.target) &&
                    !menuToggle.contains(event.target)
                ) {

                    navLinks.classList.remove(
                        "open"
                    );

                    menuToggle.classList.remove(
                        "active"
                    );

                    menuToggle.setAttribute(
                        "aria-expanded",
                        "false"
                    );

                }

            }
        );

    }


    /* =================================================
       SCROLL REVEAL
    ================================================= */

    const revealElements =
        document.querySelectorAll(
            ".reveal, .reveal-left, .reveal-right, .stagger-item"
        );


    function revealOnScroll() {

        revealElements.forEach(
            function (element) {

                const position =
                    element
                        .getBoundingClientRect()
                        .top;


                if (
                    position <
                    window.innerHeight - 80
                ) {

                    element.classList.add(
                        "visible"
                    );

                }

            }
        );

    }


    if (revealElements.length > 0) {

        window.addEventListener(
            "scroll",
            revealOnScroll,
            {
                passive: true
            }
        );

        revealOnScroll();

    }


    /* =================================================
       NAVIGATION SPARKLE EFFECT
    ================================================= */

    const navigationLinks =
        document.querySelectorAll(
            ".nav-links a"
        );


    navigationLinks.forEach(
        function (link) {

            link.addEventListener(
                "click",
                function (event) {


                    /*
                     * Do not interfere with
                     * Ctrl + Click,
                     * Shift + Click,
                     * Middle Click,
                     * Command + Click
                     */

                    if (
                        event.ctrlKey ||
                        event.shiftKey ||
                        event.metaKey ||
                        event.button !== 0
                    ) {

                        return;

                    }


                    const destination =
                        this.href;


                    /*
                     * If this is an anchor
                     * on the same page,
                     * let smooth scrolling handle it.
                     */

                    if (
                        destination.includes("#")
                    ) {

                        return;

                    }


                    event.preventDefault();


                    /* =================================================
                       RESTART CLICK ANIMATION
                    ================================================= */

                    this.classList.remove(
                        "nav-clicked"
                    );


                    void this.offsetWidth;


                    this.classList.add(
                        "nav-clicked"
                    );


                    /* =================================================
                       CREATE SPARKLES
                    ================================================= */

                    for (
                        let i = 0;
                        i < 14;
                        i++
                    ) {

                        const sparkle =
                            document.createElement(
                                "span"
                            );


                        sparkle.className =
                            "nav-sparkle";


                        const x =
                            (
                                Math.random() - 0.5
                            ) * 110;


                        const y =
                            (
                                Math.random() - 0.5
                            ) * 80;


                        sparkle.style.setProperty(
                            "--sparkle-x",
                            x + "px"
                        );


                        sparkle.style.setProperty(
                            "--sparkle-y",
                            y + "px"
                        );


                        sparkle.style.left =
                            (
                                10 +
                                Math.random() * 80
                            ) + "%";


                        sparkle.style.top =
                            (
                                20 +
                                Math.random() * 60
                            ) + "%";


                        const size =
                            2 +
                            Math.random() * 4;


                        sparkle.style.width =
                            size + "px";


                        sparkle.style.height =
                            size + "px";


                        this.appendChild(
                            sparkle
                        );


                        setTimeout(
                            function () {

                                sparkle.remove();

                            },
                            900
                        );

                    }


                    /* =================================================
                       RIPPLE EFFECT
                    ================================================= */

                    const ripple =
                        document.createElement(
                            "span"
                        );


                    ripple.className =
                        "nav-ripple";


                    ripple.style.left =
                        "50%";


                    ripple.style.top =
                        "50%";


                    this.appendChild(
                        ripple
                    );


                    setTimeout(
                        function () {

                            ripple.remove();

                        },
                        700
                    );


                    /* =================================================
                       PAGE NAVIGATION
                    ================================================= */

                    setTimeout(
                        function () {

                            window.location.href =
                                destination;

                        },
                        500
                    );

                }
            );

        }
    );


    /* =================================================
       IMAGE PREVIEW
    ================================================= */

    const imageInput =
        document.getElementById(
            "imageInput"
        );


    const imagePreview =
        document.getElementById(
            "imagePreview"
        );


    if (
        imageInput &&
        imagePreview
    ) {

        imageInput.addEventListener(
            "change",
            function () {

                const file =
                    this.files &&
                    this.files[0];


                if (!file) {

                    return;

                }


                /*
                 * Make sure the selected
                 * file is an image.
                 */

                if (
                    !file.type.startsWith(
                        "image/"
                    )
                ) {

                    alert(
                        "Please select a valid image file."
                    );

                    this.value = "";

                    return;

                }


                /*
                 * Maximum file size:
                 * 5 MB
                 */

                const maxSize =
                    5 * 1024 * 1024;


                if (
                    file.size > maxSize
                ) {

                    alert(
                        "Image must be 5MB or smaller."
                    );

                    this.value = "";

                    return;

                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        imagePreview.src =
                            event.target.result;

                        imagePreview.style.display =
                            "block";

                    };


                reader.onerror =
                    function () {

                        alert(
                            "Could not preview the image."
                        );

                    };


                reader.readAsDataURL(
                    file
                );

            }
        );

    }


    /* =================================================
       DELETE CONFIRMATION
    ================================================= */

    const deleteButtons =
        document.querySelectorAll(
            ".delete-btn"
        );


    deleteButtons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function (event) {

                    const confirmed =
                        window.confirm(
                            "Are you sure you want to delete this item?"
                        );


                    if (!confirmed) {

                        event.preventDefault();

                    }

                }
            );

        }
    );


    /* =================================================
       SMOOTH SCROLL
    ================================================= */

    const anchorLinks =
        document.querySelectorAll(
            'a[href^="#"]'
        );


    anchorLinks.forEach(
        function (link) {

            link.addEventListener(
                "click",
                function (event) {

                    const targetId =
                        this.getAttribute(
                            "href"
                        );


                    /*
                     * Ignore empty #
                     */

                    if (
                        !targetId ||
                        targetId === "#"
                    ) {

                        return;

                    }


                    let target = null;


                    try {

                        target =
                            document.querySelector(
                                targetId
                            );

                    } catch (error) {

                        return;

                    }


                    if (target) {

                        event.preventDefault();


                        target.scrollIntoView({
                            behavior: "smooth",
                            block: "start"
                        });

                    }

                }
            );

        }
    );


    /* =================================================
       ARTWORK IMAGE LAZY EFFECT
    ================================================= */

    const artworkImages =
        document.querySelectorAll(
            ".art-card img, .artist-card img"
        );


    artworkImages.forEach(
        function (image) {

            image.addEventListener(
                "load",
                function () {

                    image.classList.add(
                        "image-loaded"
                    );

                }
            );

        }
    );


    /* =================================================
       KEYBOARD ESCAPE
       CLOSE MOBILE MENU
    ================================================= */

    document.addEventListener(
        "keydown",
        function (event) {

            if (
                event.key === "Escape" &&
                navLinks &&
                menuToggle
            ) {

                navLinks.classList.remove(
                    "open"
                );

                menuToggle.classList.remove(
                    "active"
                );

                menuToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

        }
    );

});


/* =====================================================
   FUTURISTIC PARTICLES
===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const canvas =
            document.getElementById(
                "particleCanvas"
            );


        /*
         * If the canvas does not exist,
         * simply stop this animation.
         */

        if (!canvas) {

            return;

        }


        const ctx =
            canvas.getContext(
                "2d"
            );


        if (!ctx) {

            return;

        }


        let width =
            window.innerWidth;


        let height =
            window.innerHeight;


        let particles = [];


        /* =================================================
           MOUSE
        ================================================= */

        const mouse = {

            x: null,

            y: null,

            radius: 160

        };


        window.addEventListener(
            "mousemove",
            function (event) {

                mouse.x =
                    event.clientX;

                mouse.y =
                    event.clientY;

            },
            {
                passive: true
            }
        );


        window.addEventListener(
            "mouseleave",
            function () {

                mouse.x = null;

                mouse.y = null;

            }
        );


        /* =================================================
           RESIZE
        ================================================= */

        function resizeCanvas() {

            width =
                window.innerWidth;


            height =
                window.innerHeight;


            canvas.width =
                width;


            canvas.height =
                height;


            createParticles();

        }


        window.addEventListener(
            "resize",
            resizeCanvas
        );


        /* =================================================
           PARTICLE CLASS
        ================================================= */

        class Particle {


            constructor() {

                this.reset();

            }


            reset() {

                this.x =
                    Math.random() *
                    width;


                this.y =
                    Math.random() *
                    height;


                this.size =
                    Math.random() *
                    2 +
                    0.5;


                this.speedX =
                    (
                        Math.random() - 0.5
                    ) * 0.35;


                this.speedY =
                    Math.random() *
                    0.35 +
                    0.05;


                this.opacity =
                    Math.random() *
                    0.7 +
                    0.2;


                this.pulse =
                    Math.random() *
                    Math.PI *
                    2;


                this.pulseSpeed =
                    Math.random() *
                    0.025 +
                    0.005;


                this.isSpark =
                    Math.random() >
                    0.90;

            }


            update() {


                this.x +=
                    this.speedX;


                this.y +=
                    this.speedY;


                this.pulse +=
                    this.pulseSpeed;


                /*
                 * Twinkle
                 */

                const pulseValue =
                    (
                        Math.sin(
                            this.pulse
                        ) + 1
                    ) / 2;


                this.currentOpacity =
                    0.20 +
                    pulseValue *
                    this.opacity;


                /*
                 * Mouse interaction
                 */

                if (
                    mouse.x !== null &&
                    mouse.y !== null
                ) {

                    const dx =
                        this.x -
                        mouse.x;


                    const dy =
                        this.y -
                        mouse.y;


                    const distance =
                        Math.sqrt(
                            dx * dx +
                            dy * dy
                        );


                    if (
                        distance <
                        mouse.radius &&
                        distance > 0
                    ) {

                        const force =
                            (
                                mouse.radius -
                                distance
                            ) /
                            mouse.radius;


                        this.x +=
                            (
                                dx /
                                distance
                            ) *
                            force *
                            1.5;


                        this.y +=
                            (
                                dy /
                                distance
                            ) *
                            force *
                            1.5;

                    }

                }


                /*
                 * Wrap around vertically
                 */

                if (
                    this.y >
                    height + 10
                ) {

                    this.y =
                        -10;


                    this.x =
                        Math.random() *
                        width;

                }


                /*
                 * Wrap around horizontally
                 */

                if (
                    this.x >
                    width + 10
                ) {

                    this.x =
                        -10;

                }


                if (
                    this.x <
                    -10
                ) {

                    this.x =
                        width + 10;

                }

            }


            draw() {

                ctx.save();


                ctx.globalAlpha =
                    this.currentOpacity;


                if (
                    this.isSpark
                ) {

                    this.drawSpark();

                } else {

                    this.drawCircle();

                }


                ctx.restore();

            }


            drawCircle() {

                ctx.beginPath();


                ctx.arc(
                    this.x,
                    this.y,
                    this.size,
                    0,
                    Math.PI * 2
                );


                ctx.fillStyle =
                    "#ffffff";


                ctx.shadowBlur =
                    12;


                ctx.shadowColor =
                    "#d4af37";


                ctx.fill();

            }


            drawSpark() {

                const size =
                    this.size * 4;


                ctx.translate(
                    this.x,
                    this.y
                );


                ctx.strokeStyle =
                    "#ffffff";


                ctx.lineWidth =
                    1;


                ctx.shadowBlur =
                    15;


                ctx.shadowColor =
                    "#d4af37";


                /*
                 * Vertical
                 */

                ctx.beginPath();


                ctx.moveTo(
                    0,
                    -size
                );


                ctx.lineTo(
                    0,
                    size
                );


                ctx.stroke();


                /*
                 * Horizontal
                 */

                ctx.beginPath();


                ctx.moveTo(
                    -size,
                    0
                );


                ctx.lineTo(
                    size,
                    0
                );


                ctx.stroke();

            }

        }


        /* =================================================
           CREATE PARTICLES
        ================================================= */

        function createParticles() {

            particles = [];


            const amount =
                Math.min(
                    160,
                    Math.max(
                        60,
                        Math.floor(
                            (
                                width *
                                height
                            ) / 12000
                        )
                    )
                );


            for (
                let i = 0;
                i < amount;
                i++
            ) {

                particles.push(
                    new Particle()
                );

            }

        }


        /* =================================================
           CONNECT PARTICLES
        ================================================= */

        function connectParticles() {

            const maxDistance =
                120;


            for (
                let i = 0;
                i < particles.length;
                i++
            ) {

                for (
                    let j = i + 1;
                    j < particles.length;
                    j++
                ) {

                    const dx =
                        particles[i].x -
                        particles[j].x;


                    const dy =
                        particles[i].y -
                        particles[j].y;


                    const distance =
                        Math.sqrt(
                            dx * dx +
                            dy * dy
                        );


                    if (
                        distance <
                        maxDistance
                    ) {

                        const opacity =
                            (
                                1 -
                                distance /
                                maxDistance
                            ) *
                            0.10;


                        ctx.beginPath();


                        ctx.moveTo(
                            particles[i].x,
                            particles[i].y
                        );


                        ctx.lineTo(
                            particles[j].x,
                            particles[j].y
                        );


                        ctx.strokeStyle =
                            `rgba(
                                212,
                                175,
                                55,
                                ${opacity}
                            )`;


                        ctx.lineWidth =
                            0.5;


                        ctx.stroke();

                    }

                }

            }

        }


        /* =================================================
           PARTICLE ANIMATION
        ================================================= */

        function animateParticles() {

            ctx.clearRect(
                0,
                0,
                width,
                height
            );


            particles.forEach(
                function (particle) {

                    particle.update();

                    particle.draw();

                }
            );


            connectParticles();


            requestAnimationFrame(
                animateParticles
            );

        }


        resizeCanvas();

        animateParticles();

    }
);


/* =====================================================
   FUTURISTIC NETWORK FLOOR
===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const canvas =
            document.getElementById(
                "networkCanvas"
            );


        /*
         * The canvas is optional.
         * If the page does not contain it,
         * nothing happens.
         */

        if (!canvas) {

            return;

        }


        const ctx =
            canvas.getContext(
                "2d"
            );


        if (!ctx) {

            return;

        }


        let width = 0;

        let height = 0;

        let nodes = [];


        /* =================================================
           RESIZE
        ================================================= */

        function resizeNetwork() {

            const rect =
                canvas.getBoundingClientRect();


            width =
                window.innerWidth;


            height =
                rect.height;


            canvas.width =
                width;


            canvas.height =
                height;


            createNodes();

        }


        window.addEventListener(
            "resize",
            resizeNetwork
        );


        /* =================================================
           CREATE NETWORK NODES
        ================================================= */

        function createNodes() {

            nodes = [];


            const amount =
                window.innerWidth < 768
                    ? 35
                    : 85;


            for (
                let i = 0;
                i < amount;
                i++
            ) {

                nodes.push({

                    x:
                        Math.random() *
                        width,

                    y:
                        Math.random() *
                        height,

                    speedX:
                        (
                            Math.random() - 0.5
                        ) * 0.20,

                    speedY:
                        (
                            Math.random() - 0.5
                        ) * 0.10,

                    size:
                        Math.random() *
                        2.5 +
                        1,

                    pulse:
                        Math.random() *
                        Math.PI *
                        2

                });

            }

        }


        /* =================================================
           UPDATE NODES
        ================================================= */

        function updateNodes() {

            nodes.forEach(
                function (node) {

                    node.x +=
                        node.speedX;


                    node.y +=
                        node.speedY;


                    node.pulse +=
                        0.025;


                    if (
                        node.x < 0 ||
                        node.x > width
                    ) {

                        node.speedX *=
                            -1;

                    }


                    if (
                        node.y < 0 ||
                        node.y > height
                    ) {

                        node.speedY *=
                            -1;

                    }

                }
            );

        }


        /* =================================================
           DRAW NETWORK CONNECTIONS
        ================================================= */

        function drawConnections() {

            const maxDistance =
                170;


            for (
                let i = 0;
                i < nodes.length;
                i++
            ) {

                for (
                    let j = i + 1;
                    j < nodes.length;
                    j++
                ) {

                    const dx =
                        nodes[i].x -
                        nodes[j].x;


                    const dy =
                        nodes[i].y -
                        nodes[j].y;


                    const distance =
                        Math.sqrt(
                            dx * dx +
                            dy * dy
                        );


                    if (
                        distance <
                        maxDistance
                    ) {

                        const opacity =
                            (
                                1 -
                                distance /
                                maxDistance
                            ) *
                            0.32;


                        ctx.beginPath();


                        ctx.moveTo(
                            nodes[i].x,
                            nodes[i].y
                        );


                        ctx.lineTo(
                            nodes[j].x,
                            nodes[j].y
                        );


                        ctx.strokeStyle =
                            `rgba(
                                212,
                                175,
                                55,
                                ${opacity}
                            )`;


                        ctx.lineWidth =
                            0.7;


                        ctx.stroke();

                    }

                }

            }

        }


        /* =================================================
           DRAW NETWORK NODES
        ================================================= */

        function drawNodes() {

            nodes.forEach(
                function (node) {

                    const pulse =
                        (
                            Math.sin(
                                node.pulse
                            ) + 1
                        ) / 2;


                    /*
                     * Purple glow
                     */

                    ctx.beginPath();


                    ctx.arc(
                        node.x,
                        node.y,
                        node.size + 7,
                        0,
                        Math.PI * 2
                    );


                    ctx.fillStyle =
                        `rgba(
                            255,
                            0,
                            200,
                            ${0.03 + pulse * 0.08}
                        )`;


                    ctx.shadowBlur =
                        20;


                    ctx.shadowColor =
                        "#ff00cc";


                    ctx.fill();


                    /*
                     * Main node
                     */

                    ctx.beginPath();


                    ctx.arc(
                        node.x,
                        node.y,
                        node.size,
                        0,
                        Math.PI * 2
                    );


                    ctx.fillStyle =
                        "#ffffff";


                    ctx.shadowBlur =
                        15;


                    ctx.shadowColor =
                        "#d4af37";


                    ctx.fill();


                    /*
                     * Star flare
                     */

                    if (
                        node.size > 2
                    ) {

                        const flare =
                            node.size * 5;


                        ctx.strokeStyle =
                            "rgba(255,255,255,0.75)";


                        ctx.lineWidth =
                            0.7;


                        /*
                         * Horizontal
                         */

                        ctx.beginPath();


                        ctx.moveTo(
                            node.x - flare,
                            node.y
                        );


                        ctx.lineTo(
                            node.x + flare,
                            node.y
                        );


                        ctx.stroke();


                        /*
                         * Vertical
                         */

                        ctx.beginPath();


                        ctx.moveTo(
                            node.x,
                            node.y - flare
                        );


                        ctx.lineTo(
                            node.x,
                            node.y + flare
                        );


                        ctx.stroke();

                    }

                }
            );

        }


        /* =================================================
           NETWORK ANIMATION
        ================================================= */

        function animateNetwork() {

            ctx.clearRect(
                0,
                0,
                width,
                height
            );


            drawConnections();

            drawNodes();

            updateNodes();


            requestAnimationFrame(
                animateNetwork
            );

        }


        resizeNetwork();

        animateNetwork();

    }
);