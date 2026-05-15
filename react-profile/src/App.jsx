import ProfileCard from './components/ProfileCard';
import './App.css';

function App () {
  return(
    <div className="app">
      <div className="app-header">
        <a href="/" className="back-link">Вернуться на главную</a>
        <h2>React задание</h2>
      </div>
      <ProfileCard />
    </div>
  );
}

export default App
