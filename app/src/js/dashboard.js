        // Leer usuario y proyectos desde localStorage
        const user = localStorage.getItem('kanban_user') || 'Invitado';
        document.getElementById('userDisplay').textContent = user;

        function getProjects() {
            try {
                return JSON.parse(localStorage.getItem('kanban_projects') || '[]');
            } catch (e) { return [] }
        }
        function setProjects(p) { localStorage.setItem('kanban_projects', JSON.stringify(p)); }

        function render() {
            const container = document.getElementById('projectsContainer');
            const projects = getProjects();
            if (!projects.length) {
                container.innerHTML = '<tr><td colspan="4" style="text-align:center" class="small">No hay proyectos. Utiliza el botón superior para crear uno nuevo.</td></tr>';
                return;
            }
            
            const rows = projects
                .filter(proj => {
                    // Show only projects where user participates
                    const participants = proj.participants || [];
                    return participants.includes(user);
                })
                .map(proj => {
                    // Count tasks by status
                    const tasks = proj.tasks || [];
                    const counts = {
                        todo: tasks.filter(t => t.status === 'todo').length,
                        working: tasks.filter(t => t.status === 'working').length,
                        done: tasks.filter(t => t.status === 'done').length
                    };
                    
                    return `
                        <tr>
                            <td><a class="project-link" href="project.html?id=${proj.id}">${escapeHtml(proj.name)}</a></td>
                            <td><span class="task-count">${counts.todo}</span></td>
                            <td><span class="task-count">${counts.working}</span></td>
                            <td><span class="task-count">${counts.done}</span></td>
                        </tr>
                    `;
                });
            
            container.innerHTML = rows.length ? rows.join('') :
                '<tr><td colspan="4" style="text-align:center" class="small">No tienes proyectos asignados.</td></tr>';
        }

        // Modal helpers
        function openModal(id){ const m=document.getElementById(id); if(!m) return; m.classList.add('show'); m.setAttribute('aria-hidden','false'); }
        function closeModal(id){ const m=document.getElementById(id); if(!m) return; m.classList.remove('show'); m.setAttribute('aria-hidden','true'); }

        // Get all known users
        function getAllUsers(){
            const all = new Set();
            const cur = localStorage.getItem('kanban_user'); if(cur) all.add(cur);
            const allProjects = getProjects();
            allProjects.forEach(p=> (p.participants||[]).forEach(u=> all.add(u)) );
            return Array.from(all).sort();
        }

        document.getElementById('openNewProjectModal').addEventListener('click', () => {
            // Reset form
            document.getElementById('newProjectName').value = '';
            
            // Populate users list
            const list = document.getElementById('initialUsers');
            list.innerHTML = '';
            const users = getAllUsers();
            users.forEach(u => {
                const id = 'user-'+Math.random().toString(36).slice(2,8);
                const checked = u === user ? 'checked' : '';
                list.innerHTML += `<div><label><input type="checkbox" value="${escapeHtml(u)}" id="${id}" ${checked}> ${escapeHtml(u)}</label></div>`;
            });
            
            
            
            openModal('newProjectModal');
        });

        document.getElementById('cancelProjectBtn').addEventListener('click', () => closeModal('newProjectModal'));
        
        // Add new task input field
        // Helper function to update task assignees checkboxes
        function updateTaskAssignees(container) {
            const users = getAllUsers();
            container.innerHTML = `
                <div style="margin-top:8px">
                    <label style="display:block;margin-bottom:4px;color:var(--text-dim)">Asignar a:</label>
                    <div class="checkbox-list" style="max-height:80px">
                        ${users.map(u => `
                            <label style="display:flex;gap:8px;margin-bottom:4px">
                                <input type="checkbox" value="${escapeHtml(u)}"> ${escapeHtml(u)}
                            </label>
                        `).join('')}
                    </div>
                </div>
            `;
        }

  

        // Remove task input field
        document.getElementById('initialTasks').addEventListener('click', e => {
            if(e.target.classList.contains('remove-task')) {
                const groups = document.querySelectorAll('.task-input-group');
                if(groups.length > 1) {
                    e.target.closest('.task-input-group').remove();
                }
            }
        });

        document.getElementById('addProjectForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('newProjectName').value.trim();
            if (!name) return;

            // Get selected users
            const checked = Array.from(document.querySelectorAll('#initialUsers input[type=checkbox]:checked')).map(i=>i.value);
            const participants = [...new Set(checked)];
            
            // Asegurar que el usuario actual es participante
            if (!participants.includes(user)) {
                participants.push(user);
            }
            
            // Get tasks with descriptions and assignees
            const tasks = Array.from(document.querySelectorAll('.task-input-group')).map(g => {
                const name = g.querySelector('.task-input').value.trim();
                const description = g.querySelector('.task-desc').value.trim();
                const status = g.querySelector('.task-status').value;
                const assignees = Array.from(g.querySelectorAll('.task-assignees-select input[type="checkbox"]:checked'))
                    .map(cb => cb.value);
                
                return name ? { 
                    id: Date.now().toString(36) + Math.random().toString(36).slice(2),
                    name,
                    description,
                    status,
                    assignees
                } : null;
            }).filter(Boolean);

            const projects = getProjects();
            const p = { id: Date.now().toString(36), name, participants, tasks };
            projects.push(p);
            setProjects(projects);
            closeModal('newProjectModal');
            render();
        });

        function logout() {
            localStorage.removeItem('kanban_user');
            // keep projects
            window.location.href = 'login.html';
        }

        function escapeHtml(s) {
            return (s + '').replace(/[&<>\"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '\"': '&quot;', "'": '&#39;' })[c]);
        }

        render();
