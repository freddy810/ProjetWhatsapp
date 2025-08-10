import React from 'react';
import './components/css/App.css';
import MainLayout from './components/MainLayout';
import LoginWhatsapp from './components/LoginWhatsapp';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';

function MainLayoutWrapper() {
  // Récupérer l'état utilisateur passé via navigate('/home', {state: ...})
  const location = useLocation();
  const utilisateur = location.state?.utilisateur || null;

  return <MainLayout utilisateur={utilisateur} />;
}

function App() {
  return (
    <div className="container-fluid app">
      <div className="row h-100">
        <Router>
          <Routes>
            <Route path="/" element={<LoginWhatsapp />} />
            <Route path="/home" element={<MainLayoutWrapper />} />
          </Routes>
        </Router>
      </div>
    </div>
  );
}

export default App;
