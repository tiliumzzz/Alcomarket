import { useState, useEffect } from 'react';

function UsersList() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    setTimeout(() => {
      fetch('http://localhost/alkomarket/api/index.php/users')
        .then((response) => {
          if (!response.ok) {
            throw new Error(`HTTP ошибка: ${response.status}`);
          }
          return response.json();
        })
        .then((data) => {
          console.log('Данные от API:', data);
          
          if (data.success && Array.isArray(data.data)) {
            setUsers(data.data);  // ← Вот здесь исправление
            console.log('Пользователи загружены:', data.data);
          } else {
            console.warn('Неожиданный формат данных:', data);
            setUsers([]);
          }
          setLoading(false);
        })
        .catch((err) => {
          console.error('Ошибка:', err);
          setError('Ошибка загрузки данных');
          setLoading(false);
        });
    }, 2000);
  }, []);

  if (loading) {
    return <div style={{ padding: '50px', textAlign: 'center' }}>Загрузка...</div>;
  }

  if (error) {
    return <div style={{ padding: '50px', textAlign: 'center', color: 'red' }}>{error}</div>;
  }

  return (
    <div style={{ padding: '20px', maxWidth: '800px', margin: '0 auto' }}>
      <h2>Список пользователей ({users.length})</h2>
      <ul style={{ listStyle: 'none', padding: 0 }}>
        {users.map((user) => (
          <li key={user.id} style={{ 
            padding: '10px', 
            margin: '5px 0', 
            background: '#f0f0f0',
            borderRadius: '5px'
          }}>
            <strong>{user.login}</strong> - {user.email}
            <span style={{ marginLeft: '10px', fontSize: '12px', color: user.role === 'admin' ? 'red' : 'green' }}>
              ({user.role === 'admin' ? 'Админ' : 'Пользователь'})
            </span>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default UsersList;