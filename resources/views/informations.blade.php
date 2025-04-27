<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centres Naftal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: url('/image/R.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            backdrop-filter: blur(8px);
        }
        
        .overlay {
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        
        .card-wrapper {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem; /* Increased gap */
            max-width: 1000px;
            width: 100%;
        }
        
        .card {
            background-color: rgba(245, 158, 11, 0.85);
            border-radius: 16px; /* Rounded more */
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            padding: 1.5rem; /* Increased padding */
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.3s; }
        .card:nth-child(3) { animation-delay: 0.5s; }
        .card:nth-child(4) { animation-delay: 0.7s; }
        
        .card:hover {
            transform: translateY(-5px) scale(1.05); /* Slightly bigger scale on hover */
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.3);
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.1) 0%,
                rgba(255, 255, 255, 0) 60%
            );
            pointer-events: none;
        }
        
        .card h3 {
            font-size: 1.4rem; /* Increased font size */
            font-weight: 700;
            color: white;
            margin-bottom: 1rem; /* Slightly increased margin */
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .card-body {
            color: white;
            font-size: 1.1rem; /* Slightly increased font size */
            font-weight: 500;
            position: relative;
        }
        
        .card-body ul {
            list-style-type: none;
            padding-left: 0;
            margin: 0;
        }
        
        .card-body li {
            margin-bottom: 0.6rem; /* Increased margin */
            padding-bottom: 0.6rem; /* Increased padding */
            border-bottom: 1px dashed rgba(255, 255, 255, 0.3);
            display: flex;
            justify-content: space-between;
        }
        
        .card-body span {
            font-weight: 600;
        }
        
        .card-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 2rem; /* Increased icon size */
            opacity: 0.2;
            transition: all 0.3s ease;
        }
        
        .card:hover .card-icon {
            opacity: 0.4;
            transform: scale(1.1);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @media (max-width: 768px) {
            .card-wrapper {
                grid-template-columns: 1fr;
                gap: 1.5rem; /* Adjusted gap for mobile */
            }
        }
    </style>
</head>
<body>

    <div class="overlay">
        <div class="card-wrapper">
            <!-- Ordinateur Card -->
            <div class="card">
                <i class="fas fa-laptop card-icon"></i>
                <h3>Ordinateurs</h3>
                <div class="card-body">
                    <ul>
                        <li>Nombre total: <span>150</span></li>
                        <li>Site Ain-Oussara: <span>50</span></li>
                        <li>Site Djelfa: <span>40</span></li>
                        <li>Site Chiffa: <span>60</span></li>
                    </ul>
                </div>
            </div>

            <!-- Imprimante Card -->
            <div class="card">
                <i class="fas fa-print card-icon"></i>
                <h3>Imprimantes</h3>
                <div class="card-body">
                    <ul>
                        <li>Nombre total: <span>25</span></li>
                        <li>Site Ain-Oussara: <span>10</span></li>
                        <li>Site Djelfa: <span>7</span></li>
                        <li>Site Chiffa: <span>8</span></li>
                    </ul>
                </div>
            </div>

            <!-- Téléphone IP Card -->
            <div class="card">
                <i class="fas fa-phone-alt card-icon"></i>
                <h3>Téléphones IP</h3>
                <div class="card-body">
                    <ul>
                        <li>Nombre total: <span>120</span></li>
                        <li>Site Ain-Oussara: <span>40</span></li>
                        <li>Site Djelfa: <span>35</span></li>
                        <li>Site Chiffa: <span>45</span></li>
                    </ul>
                </div>
            </div>

            <!-- Point d'Accès Card -->
            <div class="card">
                <i class="fas fa-wifi card-icon"></i>
                <h3>Points d'Accès</h3>
                <div class="card-body">
                    <ul>
                        <li>Nombre total: <span>30</span></li>
                        <li>Site Ain-Oussara: <span>10</span></li>
                        <li>Site Djelfa: <span>10</span></li>
                        <li>Site Chiffa: <span>10</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.card');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-5px) scale(1.05)';
                    card.style.boxShadow = '0 12px 25px rgba(0, 0, 0, 0.3)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
                
                card.addEventListener('click', () => {
                    card.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        card.style.transform = 'translateY(-5px) scale(1.05)';
                    }, 150);
                });
            });
        });
    </script>
</body>
</html>
