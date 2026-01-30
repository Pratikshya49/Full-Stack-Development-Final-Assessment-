// 1. Live ticket status updates every 5 seconds (already there)
setInterval(() => {
    document.querySelectorAll('.ticket-row').forEach(row => {
        const id = row.dataset.ticketId;
        fetch(`../ajax/status.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                const statusEl = row.querySelector('.status');
                if (statusEl && data.status !== statusEl.textContent) {
                    statusEl.textContent = data.status;
                    statusEl.className = `status ${data.status}`;
                }
            });
    });
}, 5000);

// 2. NEW: AJAX Autocomplete Search (Bonus 10pts)
document.getElementById('quick-search')?.addEventListener('input', function() {
    const query = this.value;
    const resultsDiv = document.getElementById('search-results');
    
    if (query.length < 2) {
        resultsDiv.innerHTML = '';
        return;
    }
    
    fetch(`../ajax/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                resultsDiv.innerHTML = '<div class="no-results">No tickets found</div>';
                return;
            }
            
            resultsDiv.innerHTML = data.map(ticket => `
                <div class="search-result" onclick="selectTicket(${ticket.id})">
                    <strong>${ticket.user_name}</strong> - ${ticket.issue_type}<br>
                    <small>${ticket.priority} • ${ticket.status}</small>
                </div>
            `).join('');
        });
});

// Click result to jump to ticket
function selectTicket(id) {
    window.location.href = `edit.php?id=${id}`;
    document.getElementById('search-results').innerHTML = '';
}
