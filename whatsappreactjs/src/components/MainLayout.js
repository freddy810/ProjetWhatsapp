// components/MainLayout.js
import React from 'react';
import Sidebar from './Sidebars';
import Chat from './Chat';

export default function MainLayout({ utilisateur }) {
    return (
        <div className="d-flex h-100">
            <Sidebar utilisateur={utilisateur} />
            <Chat utilisateur={utilisateur} />
        </div>
    );
}
