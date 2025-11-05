        function qs(name) {
            const params = new URLSearchParams(location.search);
            return params.get(name);
        }
        const projectId = qs('id');
        if (!projectId) {
            alert('Proyecto no especificado');
            window.location.href = 'dashboard.html';
        }

        function getProjects() {
            try { return JSON.parse(localStorage.getItem('kanban_projects') || '[]'); } catch (e) { return [] }
        }
        function setProjects(p) { localStorage.setItem('kanban_projects', JSON.stringify(p)); }

        let projects = getProjects();
        let project = projects.find(p => p.id === projectId);
        if (!project) {
            alert('Proyecto no encontrado');
            window.location.href = 'dashboard.html';
        }

        document.getElementById('projName').textContent = project.name;
        
        function updateStats() {
            // Participantes
            const participants = project.participants || [];
            document.getElementById('participantsCount').textContent = participants.length;
            
            // Tareas totales
            const tasks = project.tasks || [];
            document.getElementById('totalTasks').textContent = tasks.length;
            
            // Porcentaje completado
            const doneTasks = tasks.filter(t => t.status === 'done').length;
            const completionRate = tasks.length ? Math.round((doneTasks / tasks.length) * 100) : 0;
            document.getElementById('completionRate').textContent = completionRate + '%';
        }
        updateStats();

        function renderTasks() {
            const map = { todo: 'col-todo', working: 'col-working', done: 'col-done' };
            ['todo', 'working', 'done'].forEach(s => document.getElementById(map[s]).innerHTML = '');
            (project.tasks || []).forEach(t => {
                const el = document.createElement('div');
                el.className = 'task';
                el.innerHTML = `<span>${escapeHtml(t.name)} <span class="small">${t.assignees ? (' - ' + t.assignees.join(', ')) : ''}</span></span>`;
                const actions = document.createElement('div');
                const editBtn = document.createElement('button'); editBtn.className = 'btn'; editBtn.textContent = 'Editar';
                editBtn.onclick = () => editTask(t.id);
                const delBtn = document.createElement('button'); delBtn.className = 'btn danger'; delBtn.textContent = 'Borrar';
                delBtn.onclick = () => deleteTask(t.id);
                const moveSelect = document.createElement('select');
                ['todo', 'working', 'done'].forEach(s => {
                    const o = document.createElement('option'); o.value = s; o.textContent = s === t.status ? s + ' ✓' : s; if (s === t.status) o.selected = true; moveSelect.appendChild(o);
                });
                moveSelect.onchange = (e) => moveTask(t.id, e.target.value);
                actions.appendChild(moveSelect);
                actions.appendChild(editBtn);
                actions.appendChild(delBtn);
                el.appendChild(actions);
                document.getElementById(map[t.status || 'todo']).appendChild(el);
            });
        }

        // Helpers for modal open/close
        function openModal(id){ const m=document.getElementById(id); if(!m) return; m.classList.add('show'); m.setAttribute('aria-hidden','false'); }
        function closeModal(id){ const m=document.getElementById(id); if(!m) return; m.classList.remove('show'); m.setAttribute('aria-hidden','true'); }

        // Get list of all known users (from projects participants + current user)
        function getAllUsers(){
            const all = new Set();
            const cur = localStorage.getItem('kanban_user'); if(cur) all.add(cur);
            const allProjects = getProjects();
            allProjects.forEach(p=> (p.participants||[]).forEach(u=> all.add(u)) );
            return Array.from(all).sort();
        }

        // Open task modal and populate assignees choices
        document.getElementById('openTaskModal').addEventListener('click', ()=>{
            document.getElementById('modalTaskName').value='';
            document.getElementById('modalTaskStatus').value='todo';
            const list = document.getElementById('modalTaskAssignees'); list.innerHTML='';
            // Solo mostrar participantes del proyecto
            const users = project.participants || [];
            if (users.length === 0) {
                list.innerHTML = '<div class="small">No hay participantes en el proyecto. Añade participantes primero.</div>';
            } else {
                users.forEach(u=>{
                    const id = 'assg-'+Math.random().toString(36).slice(2,8);
                    const div = document.createElement('div');
                    div.innerHTML = `<label><input type="checkbox" value="${escapeHtml(u)}" id="${id}"> ${escapeHtml(u)}</label>`;
                    list.appendChild(div);
                });
            }
            openModal('taskModal');
        });

        document.getElementById('cancelTaskBtn').addEventListener('click', ()=> closeModal('taskModal'));
        document.getElementById('saveTaskBtn').addEventListener('click', ()=>{
            const name = document.getElementById('modalTaskName').value.trim();
            const description = document.getElementById('modalTaskDesc').value.trim();
            const status = document.getElementById('modalTaskStatus').value;
            if(!name){ alert('Introduce un nombre para la tarea'); return; }
            const checked = Array.from(document.querySelectorAll('#modalTaskAssignees input[type=checkbox]:checked')).map(i=>i.value);
            
            // Verificar que todos los asignados sean participantes del proyecto
            const nonParticipants = checked.filter(user => !(project.participants || []).includes(user));
            if (nonParticipants.length > 0) {
                alert(`Los siguientes usuarios no son participantes del proyecto: ${nonParticipants.join(', ')}`);
                return;
            }
            
            const editingId = document.getElementById('editingTaskId').value;
            if(editingId) {
                // Editing existing task
                const t = project.tasks.find(x => x.id === editingId);
                if(t) {
                    t.name = name;
                    t.description = description;
                    t.status = status;
                    t.assignees = checked;
                }
            } else {
                // Adding new task
                const t = { 
                    id: Date.now().toString(36), 
                    name, 
                    description,
                    status, 
                    assignees: checked 
                };
                project.tasks = project.tasks || [];
                project.tasks.push(t);
            }
            saveAndRender();
            closeModal('taskModal');
            
            // Reset modal for next use
            document.getElementById('taskModalTitle').textContent = 'Añadir tarea';
            document.getElementById('editingTaskId').value = '';
        });

        // Open user modal: list all users with checkboxes, allow adding new
        document.getElementById('openUserModal').addEventListener('click', ()=>{
            const list = document.getElementById('modalUserList'); list.innerHTML='';
            const users = getAllUsers();
            users.forEach(u=>{
                const id = 'user-'+Math.random().toString(36).slice(2,8);
                const div = document.createElement('div');
                const checked = (project.participants||[]).includes(u) ? 'checked' : '';
                div.innerHTML = `<label><input type="checkbox" value="${escapeHtml(u)}" id="${id}" ${checked}> ${escapeHtml(u)}</label>`;
                list.appendChild(div);
            });
            document.getElementById('modalNewUser').value='';
            openModal('userModal');
        });

        document.getElementById('cancelUserBtn').addEventListener('click', ()=> closeModal('userModal'));
        document.getElementById('saveUserBtn').addEventListener('click', ()=>{
            const newUser = document.getElementById('modalNewUser').value.trim();
            const checked = Array.from(document.querySelectorAll('#modalUserList input[type=checkbox]:checked')).map(i=>i.value);
            project.participants = project.participants || [];
            // Merge checked users
            checked.forEach(u=>{ if(!project.participants.includes(u)) project.participants.push(u); });
            // If new user provided, add to participants
            if(newUser){ if(!project.participants.includes(newUser)) project.participants.push(newUser); }
            saveAndRender();
            closeModal('userModal');
        });

        function editTask(taskId) {
            const t = project.tasks.find(x => x.id === taskId);
            if (!t) return;
            
            document.getElementById('taskModalTitle').textContent = 'Editar tarea';
            document.getElementById('modalTaskName').value = t.name;
            document.getElementById('modalTaskStatus').value = t.status || 'todo';
            document.getElementById('editingTaskId').value = taskId;
            
            const list = document.getElementById('modalTaskAssignees'); 
            list.innerHTML = '';
            const users = project.participants || getAllUsers();
            users.forEach(u => {
                const id = 'assg-'+Math.random().toString(36).slice(2,8);
                const div = document.createElement('div');
                const checked = (t.assignees || []).includes(u) ? 'checked' : '';
                div.innerHTML = `<label><input type="checkbox" value="${escapeHtml(u)}" id="${id}" ${checked}> ${escapeHtml(u)}</label>`;
                list.appendChild(div);
            });
            openModal('taskModal');
        }

        function deleteTask(taskId) {
            if (!confirm('¿Eliminar esta tarea?')) return;
            project.tasks = project.tasks.filter(t => t.id !== taskId);
            saveAndRender();
        }

        function moveTask(taskId, newStatus) {
            const t = project.tasks.find(x => x.id === taskId);
            if (!t) return; t.status = newStatus; saveAndRender();
        }

        document.getElementById('editProjBtn').addEventListener('click', () => {
            document.getElementById('editProjectName').value = project.name;
            openModal('editProjectModal');
        });

        document.getElementById('confirmEditProjectBtn').addEventListener('click', () => {
            const name = document.getElementById('editProjectName').value.trim();
            if (!name) return;
            project.name = name;
            saveAndRender();
            closeModal('editProjectModal');
        });

        document.getElementById('deleteProjBtn').addEventListener('click', () => {
            openModal('deleteProjectModal');
        });

        document.getElementById('confirmDeleteProjectBtn').addEventListener('click', () => {
            projects = projects.filter(p => p.id !== projectId);
            setProjects(projects);
            window.location.href = 'dashboard.html';
        });

        function saveAndRender() {
            // update in projects list
            const idx = projects.findIndex(p => p.id === projectId);
            if (idx !== -1) projects[idx] = project; else projects.push(project);
            setProjects(projects);
            document.getElementById('projName').textContent = project.name;
            updateStats();
            renderTasks();
        }

        function goBack() { window.location.href = 'dashboard.html'; }

        function escapeHtml(s) {
            return (s + '').replace(/[&<>\"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '\"': '&quot;', "'": '&#39;' })[c]);
        }

        renderTasks();