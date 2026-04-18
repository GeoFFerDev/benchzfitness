import './bootstrap';

// Use event delegation on the document to handle clicks
document.addEventListener('click', (e) => {
    // Check if the click was on a member row (or inside one)
    const row = e.target.closest('.member-row');
    
    if (row) {
        const id = row.dataset.id;
        const name = row.dataset.name;

        // 1. Handle Selection Highlighting
        document.querySelectorAll('.member-row').forEach(r => r.classList.remove('selected'));
        row.classList.add('selected');

        // 2. Populate and Show Modal
        document.getElementById('memberActionModal').classList.remove('hidden');
        document.getElementById('modalName').innerText = name;
        document.getElementById('editLink').href = `/memberManagement/${id}/edit`;
        document.getElementById('editLinkStatus').href = `/memberManagement/${id}/status`;
        document.getElementById('deleteForm').action = `/memberManagement/${id}`;
    }
});

// Close modal function
window.closeModal = function() {
    document.getElementById('memberActionModal').classList.add('hidden');
    document.querySelectorAll('.member-row').forEach(r => r.classList.remove('selected'));
};