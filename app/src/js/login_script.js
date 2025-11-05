        /*
        * param :id: correspond to form 
        * 
        * able visluazation on web page 
        * gets proc by cliccking on form buttom with action recall
        */
        function showOverlay(id) {
            const o = document.getElementById(id);
            if (!o) return;
            o.classList.add('show');
            o.setAttribute('aria-hidden', 'false');
            const first = o.querySelector('input');
            if (first) first.focus();
        }

        /*
        * param :id: correspond to form 
        * 
        * disables visluazation on web page 
        * gets proc by key:ESC: clicking outside or close buttom
        */
        function hideOverlay(id) {
            const o = document.getElementById(id);
            if (!o) return;
            o.classList.remove('show');
            o.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) hideOverlay(overlay.id);
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.overlay.show').forEach(o => hideOverlay(o.id));
            }
        });

        document.querySelectorAll('[data-close]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.currentTarget.getAttribute('data-close');
                hideOverlay(id);
            });
        });

        document.getElementById('openLogin').addEventListener('click', () => {
            showOverlay('loginOverlay');
        });
        document.getElementById('openRegister').addEventListener('click', () => showOverlay('registerOverlay'));

        // Manejo del submit del formulario de login: guardar usuario y redirigir al dashboard
        const loginForm = document.getElementById('loginForm');
        const loginError = document.getElementById('loginError');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if (loginError) loginError.style.display = 'none';
                const nombre = document.getElementById('loginNombre').value.trim();
                const pass = document.getElementById('loginPass').value;
                if (!nombre) {
                    if (loginError) {
                        loginError.textContent = 'Por favor, introduce nombre de usuario.';
                        loginError.style.display = 'block';
                    }
                    return;
                }
                // Guardar usuario en localStorage (sin verificación)
                localStorage.setItem('kanban_user', nombre);
                if (!localStorage.getItem('kanban_projects')) {
                    localStorage.setItem('kanban_projects', JSON.stringify([]));
                }
                // Redirigir al dashboard (archivo en la misma carpeta)
                window.location.href = 'dashboard.html';
            });
        }