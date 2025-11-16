@extends('front.layouts.app')

@section('title', 'Benvenuto a Hogwarts')

@section('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: white;
    padding: 100px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"><path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" fill="%23fff" opacity="0.05"></path></svg>');
    background-size: cover;
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: bold;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    font-size: 1.5rem;
    margin-bottom: 40px;
    opacity: 0.9;
}

.cta-buttons .btn {
    margin: 10px;
    padding: 15px 40px;
    font-size: 1.1rem;
    border-radius: 50px;
    transition: all 0.3s;
}

.btn-primary {
    background: #c19a6b;
    border: none;
    color: white;
}

.btn-primary:hover {
    background: #a67c52;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(193, 154, 107, 0.4);
}

.btn-outline-light {
    border: 2px solid white;
}

.btn-outline-light:hover {
    background: white;
    color: #1a1a2e;
    transform: translateY(-2px);
}

.features-section {
    padding: 80px 0;
    background: #f8f9fa;
}

.feature-card {
    text-align: center;
    padding: 30px;
    margin: 20px 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.feature-card:hover {
    transform: translateY(-10px);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 20px;
    color: #c19a6b;
}

.feature-title {
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 15px;
    color: #2c3e50;
}

.feature-description {
    color: #7f8c8d;
}

.houses-section {
    padding: 80px 0;
    background: white;
}

.house-card {
    text-align: center;
    padding: 30px;
    margin: 20px 0;
    border-radius: 10px;
    color: white;
    transition: transform 0.3s;
}

.house-card:hover {
    transform: scale(1.05);
}

.house-gryffindor { background: linear-gradient(135deg, #740001 0%, #ae0001 100%); }
.house-slytherin { background: linear-gradient(135deg, #1a472a 0%, #2a623d 100%); }
.house-hufflepuff { background: linear-gradient(135deg, #ecb939 0%, #f0c75e 100%); color: #333; }
.house-ravenclaw { background: linear-gradient(135deg, #0e1a40 0%, #222f5b 100%); }

.house-name {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 15px 0;
}

.house-motto {
    font-style: italic;
    opacity: 0.9;
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-content">
        <div class="container">
            <h1 class="hero-title">Benvenuto a Hogwarts</h1>
            <p class="hero-subtitle">Inizia la tua avventura nel mondo magico</p>
            <div class="cta-buttons">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-primary">Registrati Ora</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light">Accedi</a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-primary">Vai alla Dashboard</a>
                    @if(!Auth::user()->team)
                        <a href="/maps/great-hall/sort" class="btn btn-outline-light">Vai allo Smistamento</a>
                    @endif
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4">Esplora il Mondo Magico</h2>
            <p class="lead">Vivi un'esperienza unica nel GDR di Harry Potter</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h3 class="feature-title">Smistamento nelle Case</h3>
                    <p class="feature-description">Il Cappello Parlante sceglierà la casa più adatta a te tra Grifondoro, Serpeverde, Tassorosso e Corvonero</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">✨</div>
                    <h3 class="feature-title">Impara la Magia</h3>
                    <p class="feature-description">Frequenta le lezioni, impara incantesimi e pozioni, e diventa un mago esperto</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">⚔️</div>
                    <h3 class="feature-title">Duelli Magici</h3>
                    <p class="feature-description">Sfida altri studenti in epici duelli magici e dimostra le tue abilità</p>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">🗺️</div>
                    <h3 class="feature-title">Esplora Hogwarts</h3>
                    <p class="feature-description">Scopri le aule, i corridoi segreti e tutti i luoghi magici del castello</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3 class="feature-title">Fai Amicizia</h3>
                    <p class="feature-description">Conosci altri studenti, stringi alleanze e vivi avventure insieme</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3 class="feature-title">Coppa delle Case</h3>
                    <p class="feature-description">Guadagna punti per la tua casa e aiutala a vincere la Coppa delle Case</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Houses Section -->
<div class="houses-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4">Le Quattro Case</h2>
            <p class="lead">Quale sarà la tua destinazione?</p>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="house-card house-gryffindor">
                    <div style="font-size: 3rem;">🦁</div>
                    <h3 class="house-name">Grifondoro</h3>
                    <p class="house-motto">"Il coraggio risiede nei cuori audaci"</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="house-card house-slytherin">
                    <div style="font-size: 3rem;">🐍</div>
                    <h3 class="house-name">Serpeverde</h3>
                    <p class="house-motto">"L'astuzia porta al potere"</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="house-card house-hufflepuff">
                    <div style="font-size: 3rem;">🦡</div>
                    <h3 class="house-name">Tassorosso</h3>
                    <p class="house-motto">"La lealtà è la vera forza"</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="house-card house-ravenclaw">
                    <div style="font-size: 3rem;">🦅</div>
                    <h3 class="house-name">Corvonero</h3>
                    <p class="house-motto">"La saggezza illumina la via"</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="hero-section" style="padding: 60px 0;">
    <div class="container text-center">
        <h2 class="mb-4">Pronto a iniziare la tua avventura?</h2>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Iscriviti a Hogwarts</a>
        @else
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Entra nel Castello</a>
        @endguest
    </div>
</div>
@endsection
