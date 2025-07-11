<footer class="modern-footer">
    <div class="footer-container">
        <!-- Section principale -->
        <div class="footer-main">
            <div class="footer-grid">
                <!-- Marque et description -->
                <div class="footer-brand-section">
                    <div class="footer-brand">
                        <img src="{{ asset('images/logo.png') }}" alt="Lebayo" class="footer-logo">
                        {{-- <span class="brand-name">Lebayo</span> --}}
                    </div>
                    <p class="footer-description">
                        Votre plateforme de livraison rapide et fiable. 
                        Découvrez les meilleurs commerces de votre région.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link facebook" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-link twitter" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link instagram" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-link linkedin" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Liens rapides -->
                <div class="footer-section">
                    <h4 class="footer-title">Liens rapides</h4>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="#about">À propos</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#contact">Contact</a></li>
                        <li><a href="#help">Aide</a></li>
                    </ul>
                </div>
                
                <!-- Catégories -->
                <div class="footer-section">
                    <h4 class="footer-title">Catégories</h4>
                    <ul class="footer-links">
                        <li><a href="#">Restaurants</a></li>
                        <li><a href="#">Pharmacies</a></li>
                        <li><a href="#">Supermarchés</a></li>
                        <li><a href="#">Boutiques</a></li>
                        <li><a href="#">Services</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div class="footer-section">
                    <h4 class="footer-title">Contact</h4>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>contact@lebayo.com</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+225 07 88 66 13 75</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Daloa, Côte d'Ivoire</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Séparateur -->
        <div class="footer-divider"></div>
        
        <!-- Pied de page -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <p>&copy; {{ date('Y') }} Lebayo. Tous droits réservés.</p>
                </div>
                <div class="footer-credits">
                    <p>Designed by <a href="https://thuppers.com" target="_blank" rel="noopener noreferrer" class="thuppers-link">Thuppers Inc</a></p>
                </div>
                <div class="footer-policies">
                    <a href="#privacy">Politique de confidentialité</a>
                    <a href="#terms">Conditions d'utilisation</a>
                </div>
            </div>
        </div>
    </div>
</footer> 