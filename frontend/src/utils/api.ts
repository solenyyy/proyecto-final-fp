export function getActivities() {
    return fetch('/api/activities')
        .then(res => res.json())
        .then(data => data['member'] ?? [])}