import { useState } from 'react';
import './ProfileCard.css';
function ProfileCard() {
    const[avatarUrl, setAvatarUrl] = useState(null);
    const handleImageUpload = (event) => {
        const file = event.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onloadend = () => {
                setAvatarUrl(reader.result);
            };
            reader.readAsDataURL(file);
        }
    };
    return (
        <div className="profile-card">
            <h1>Моя визитка</h1>
            <div className="avatar-container">
                {avatarUrl ? (
                    <img src={avatarUrl} alt="Аватар" className="avatar" />
                ) : (
                    <div className="avatar-placeholder">📷</div>
                )}
            </div>
        
            <div className="file-input-wrapper">
                <label htmlFor="avatar-upload" className="upload-btn">
                    Загрузить аватарку
                </label>
                <input
                    type="file"
                    id="avatar-upload"
                    accept="image/*"
                    onChange={handleImageUpload}
                    style={{ display: 'none' }}
                />
            </div>

            <ul className="info-list">
                <li><strong>Имя студента:</strong> Абатуров Никита</li>
                <li><strong>Специальность:</strong> Информатика и вычислительная техника</li>
                <li><strong>Номер группы:</strong> БИВТ-24-4</li>
            </ul>

            <div className="description">
                <h3>О себе:</h3>
                <p>Студент 2 курса, 19 лет. Люблю Манчестер Юнайтед</p>
            </div>

            <footer className="card-footer">
                <p>© 2026 | Контакты: m2416440@edu.misis.ru</p>
            </footer>
        </div>
  );
}

export default ProfileCard;