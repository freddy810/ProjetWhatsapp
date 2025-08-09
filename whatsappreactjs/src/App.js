import React from 'react';
import './components/css/App.css';
import Sidebar from './components/Sidebars';
import Chat from './components/Chat';

function App() {
  return (
    <div className="container-fluid app">
      <div className="row h-100">
        <Sidebar />
        <Chat />
      </div>
    </div>

  );
}

export default App;
