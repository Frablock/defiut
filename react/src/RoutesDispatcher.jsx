import React from 'react';
import { Navigate, Route, Routes } from 'react-router-dom';
import ErrorPage from './errorPage/Error';
import Login from './login/Login';
import 'bootstrap/dist/css/bootstrap.min.css';
import Register from './register/Register';
import Lobby from './lobby/Lobby';
import Profil from './profil/Profil'
import Defis from './defis/Defis';
import LegalNotices from './legal/LegalNotices';
import FAQ from './faq/Faq';
import CGU from './cgu/Cgu';

function RoutesDispatcher(props) {
  return (
    <>
      <Routes>
        <Route 
          path="/"
          element={
              <Navigate to="/lobby" replace />
        } 
        />
        <Route 
          path="/lobby"
          element={<Lobby {...props}/>}
        />
        <Route 
          path="/lobby/:category"
          element={<Lobby {...props}/>}
        />
        <Route 
          path="/login"
          element={<Login {...props}/>}
        />
        <Route 
          path="/register"
          element={<Register {...props}/>}
        />
        <Route
          path="/profil"
          element={<Profil {...props}/>}
        />
        <Route
          path="/defis/:id"
          element={<Defis {...props}/>}
        />
        <Route
          path="/legal-notices"
          element={<LegalNotices {...props}/>}
        />
        <Route
          path="/faq"
          element={<FAQ {...props}/>}
        />
        <Route
          path="/cgu"
          element={<CGU {...props}/>}
        />
        <Route 
          path="*"
          element={<ErrorPage {...props}/>}
        />
      </Routes>
    </>
  );
}

export default RoutesDispatcher;