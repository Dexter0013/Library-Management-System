<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Welcome') · LibraryMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #090616;
            overflow-x: hidden;
            position: relative;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        /* 3D WebGL Canvas */
        #webgl-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        /* Ambient Cosmic Glow */
        .ambient-glow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            background: radial-gradient(circle at 15% 25%, rgba(111, 66, 193, 0.4) 0%, transparent 45%),
                        radial-gradient(circle at 85% 75%, rgba(0, 245, 212, 0.25) 0%, transparent 45%),
                        radial-gradient(circle at 50% 50%, rgba(35, 12, 69, 0.8) 0%, #080512 100%);
            pointer-events: none;
        }

        /* Foreground Content */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 2.5rem 1rem;
        }

        /* Frosted Glassmorphism Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 1.25rem;
            box-shadow: 0 1.5rem 4rem rgba(0, 0, 0, 0.5),
                        0 0.5rem 2rem rgba(111, 66, 193, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-card:hover {
            box-shadow: 0 2rem 4.5rem rgba(0, 0, 0, 0.55),
                        0 0.75rem 2.5rem rgba(111, 66, 193, 0.35);
        }

        /* Animated Floating 3D Badge */
        .brand-badge {
            width: 64px;
            height: 64px;
            border-radius: 1.25rem;
            background: linear-gradient(135deg, #7c4ec9 0%, #48cae4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0.75rem 2rem rgba(124, 78, 201, 0.55);
            animation: pulse3DBadge 4s ease-in-out infinite alternate;
        }

        @keyframes pulse3DBadge {
            0% { transform: translateY(0) rotate(0deg); box-shadow: 0 0.5rem 1.5rem rgba(124, 78, 201, 0.5); }
            100% { transform: translateY(-7px) rotate(3deg); box-shadow: 0 1.25rem 2.5rem rgba(72, 202, 228, 0.6); }
        }

        .form-control {
            border-radius: 0.6rem;
            padding: 0.7rem 0.9rem;
            border: 1px solid #d3d3df;
        }

        .form-control:focus {
            border-color: #7c4ec9;
            box-shadow: 0 0 0 0.25rem rgba(124, 78, 201, 0.22);
        }

        .btn-brand {
            background: linear-gradient(135deg, #6f42c1 0%, #4e2a84 100%);
            border: none;
            color: #ffffff !important;
            font-weight: 600;
            padding: 0.7rem 1.25rem;
            border-radius: 0.6rem;
            box-shadow: 0 0.5rem 1.5rem rgba(111, 66, 193, 0.35);
            transition: all 0.25s ease;
        }

        .btn-brand:hover {
            background: linear-gradient(135deg, #814edb 0%, #5d349c 100%);
            box-shadow: 0 0.75rem 2rem rgba(111, 66, 193, 0.55);
            transform: translateY(-2px);
        }

        .text-glow {
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.7), 0 0 25px rgba(157, 78, 221, 0.5);
        }
    </style>
</head>
<body>
    <!-- Ambient Atmospheric Glow -->
    <div class="ambient-glow"></div>

    <!-- 3D WebGL Canvas -->
    <canvas id="webgl-bg"></canvas>

    <!-- Foreground Auth Container -->
    <div class="auth-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-11 col-sm-8 col-md-6 col-lg-5">
                    <div class="text-center mb-4">
                        <div class="brand-badge mx-auto mb-3">
                            <i class="bi bi-book-half text-white fs-2"></i>
                        </div>
                        <h1 class="h3 text-white fw-bold mb-1 text-glow">LibraryMS</h1>
                        <p class="text-white-50 small mb-0">Smart • Seamless • Catalogued</p>
                    </div>
                    <div class="card auth-card p-4 p-sm-5">
                        @include('partials.flash')
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Three.js for 3D Moving Scene -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        (function() {
            if (typeof THREE === 'undefined') return;

            const canvas = document.getElementById('webgl-bg');
            const scene = new THREE.Scene();
            scene.fog = new THREE.FogExp2(0x090616, 0.0016);

            const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1500);
            camera.position.z = 380;

            const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Dynamic Lights
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
            scene.add(ambientLight);

            const pointLight1 = new THREE.PointLight(0x9d4edd, 2.5, 900);
            pointLight1.position.set(200, 200, 100);
            scene.add(pointLight1);

            const pointLight2 = new THREE.PointLight(0x00f5d4, 2, 900);
            pointLight2.position.set(-200, -150, 150);
            scene.add(pointLight2);

            // 1. Floating 3D Geometric Books & Polyhedra
            const objectsGroup = new THREE.Group();
            scene.add(objectsGroup);

            const geometries = [
                new THREE.BoxGeometry(22, 32, 6),       // 3D Book
                new THREE.BoxGeometry(18, 26, 5),       // Compact 3D Book
                new THREE.IcosahedronGeometry(13, 0),   // Crystal
                new THREE.OctahedronGeometry(14, 0),    // Gem
                new THREE.TorusGeometry(11, 3.5, 16, 40)// Halo Ring
            ];

            const materials = [
                new THREE.MeshStandardMaterial({
                    color: 0x7c4ec9,
                    metalness: 0.35,
                    roughness: 0.35,
                    transparent: true,
                    opacity: 0.85
                }),
                new THREE.MeshStandardMaterial({
                    color: 0x48cae4,
                    metalness: 0.45,
                    roughness: 0.25,
                    transparent: true,
                    opacity: 0.80
                }),
                new THREE.MeshStandardMaterial({
                    color: 0xe0aaff,
                    metalness: 0.2,
                    roughness: 0.5,
                    wireframe: true
                }),
                new THREE.MeshStandardMaterial({
                    color: 0x00f5d4,
                    metalness: 0.5,
                    roughness: 0.2,
                    wireframe: true
                })
            ];

            const floaters = [];
            const count = 36;
            for (let i = 0; i < count; i++) {
                const geom = geometries[Math.floor(Math.random() * geometries.length)];
                const mat = materials[Math.floor(Math.random() * materials.length)];
                const mesh = new THREE.Mesh(geom, mat);

                mesh.position.x = (Math.random() - 0.5) * 850;
                mesh.position.y = (Math.random() - 0.5) * 650;
                mesh.position.z = (Math.random() - 0.5) * 450;

                mesh.rotation.x = Math.random() * Math.PI;
                mesh.rotation.y = Math.random() * Math.PI;
                mesh.rotation.z = Math.random() * Math.PI;

                const scale = 0.75 + Math.random() * 0.9;
                mesh.scale.set(scale, scale, scale);

                objectsGroup.add(mesh);

                floaters.push({
                    mesh: mesh,
                    rotSpeedX: (Math.random() - 0.5) * 0.016,
                    rotSpeedY: (Math.random() - 0.5) * 0.02,
                    rotSpeedZ: (Math.random() - 0.5) * 0.012,
                    floatSpeed: 0.6 + Math.random() * 1.3,
                    initialY: mesh.position.y,
                    floatOffset: Math.random() * Math.PI * 2
                });
            }

            // 2. 3D Dynamic Particle Wave Grid (Luminous Undulating Grid)
            const gridX = 55;
            const gridZ = 35;
            const separation = 24;
            const particlesCount = gridX * gridZ;
            const particleGeom = new THREE.BufferGeometry();
            const positions = new Float32Array(particlesCount * 3);

            let idx = 0;
            for (let ix = 0; ix < gridX; ix++) {
                for (let iz = 0; iz < gridZ; iz++) {
                    positions[idx * 3] = (ix - gridX / 2) * separation;
                    positions[idx * 3 + 1] = -150;
                    positions[idx * 3 + 2] = (iz - gridZ / 2) * separation - 60;
                    idx++;
                }
            }

            particleGeom.setAttribute('position', new THREE.BufferAttribute(positions, 3));

            // Particle Glow Texture
            const pCanvas = document.createElement('canvas');
            pCanvas.width = 32;
            pCanvas.height = 32;
            const pCtx = pCanvas.getContext('2d');
            const pGrad = pCtx.createRadialGradient(16, 16, 0, 16, 16, 16);
            pGrad.addColorStop(0, 'rgba(255, 255, 255, 1)');
            pGrad.addColorStop(0.3, 'rgba(157, 78, 221, 0.85)');
            pGrad.addColorStop(0.7, 'rgba(0, 245, 212, 0.4)');
            pGrad.addColorStop(1, 'rgba(0, 0, 0, 0)');
            pCtx.fillStyle = pGrad;
            pCtx.fillRect(0, 0, 32, 32);
            const pTexture = new THREE.CanvasTexture(pCanvas);

            const particleMat = new THREE.PointsMaterial({
                size: 9,
                map: pTexture,
                transparent: true,
                opacity: 0.85,
                blending: THREE.AdditiveBlending,
                depthWrite: false
            });

            const particleSystem = new THREE.Points(particleGeom, particleMat);
            scene.add(particleSystem);

            // Mouse Interaction & Smooth Parallax Tracking
            let mouseX = 0;
            let mouseY = 0;
            let targetX = 0;
            let targetY = 0;
            const halfW = window.innerWidth / 2;
            const halfH = window.innerHeight / 2;

            window.addEventListener('mousemove', function(e) {
                mouseX = (e.clientX - halfW) * 0.25;
                mouseY = (e.clientY - halfH) * 0.25;
            });

            window.addEventListener('resize', function() {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });

            // Render & Animation Loop
            let clock = new THREE.Clock();

            function animate() {
                requestAnimationFrame(animate);

                const time = clock.getElapsedTime();

                // Smooth Parallax Lerp
                targetX += (mouseX - targetX) * 0.04;
                targetY += (mouseY - targetY) * 0.04;

                camera.position.x = targetX;
                camera.position.y = -targetY + 20;
                camera.lookAt(scene.position);

                // Gentle group orbital motion
                objectsGroup.rotation.y = time * 0.035;

                // Animate floating items
                for (let i = 0; i < floaters.length; i++) {
                    const f = floaters[i];
                    f.mesh.rotation.x += f.rotSpeedX;
                    f.mesh.rotation.y += f.rotSpeedY;
                    f.mesh.rotation.z += f.rotSpeedZ;
                    f.mesh.position.y = f.initialY + Math.sin(time * f.floatSpeed + f.floatOffset) * 18;
                }

                // Animate 3D Particle Wave
                const pos = particleGeom.attributes.position.array;
                let pIndex = 0;
                for (let ix = 0; ix < gridX; ix++) {
                    for (let iz = 0; iz < gridZ; iz++) {
                        pos[pIndex * 3 + 1] = -150 +
                            (Math.sin((ix + time * 2) * 0.28) * 24) +
                            (Math.sin((iz + time * 1.6) * 0.38) * 24);
                        pIndex++;
                    }
                }
                particleGeom.attributes.position.needsUpdate = true;

                // Orbit point lights for dynamic specular shifts
                pointLight1.position.x = Math.sin(time * 0.6) * 320;
                pointLight1.position.y = Math.cos(time * 0.45) * 220;
                pointLight2.position.x = -Math.cos(time * 0.5) * 320;
                pointLight2.position.z = Math.sin(time * 0.7) * 220;

                renderer.render(scene, camera);
            }

            animate();
        })();
    </script>
</body>
</html>
