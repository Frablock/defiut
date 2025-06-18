import { Navigate, Route, Routes } from 'react-router-dom';
import ErrorPage from './errorPage/Error';
import Login from './login/Login';
import 'bootstrap/dist/css/bootstrap.min.css';
import Register from './register/Register';
import Lobby from './lobby/Lobby';
import Profil from './profil/Profil'
import Defis from './defis/Defis';
import CreateDefis from './createDefis/CreateDefis';
import LegalNotices from './legal/LegalNotices';
import FAQ from './faq/Faq';
import CGU from './cgu/Cgu';
import LobbyCategory from './lobby/LobbyCategory';

function RoutesDispatcher(props) {
  const { isLogedIn } = props;
  console.log(isLogedIn)
  return (
    <>
      <Routes>
        <Route 
          path="/"
          element={<Navigate to="/lobby" replace />}
        />
        
        <Route 
          path="/lobby"
          element={<Lobby {...props}/>}
        />
        <Route 
          path="/lobby/:category"
          element={<LobbyCategory {...props}/>}
        />
        {/* Redirect to lobby if already logged in */}
        <Route 
          path="/login"
          element={isLogedIn ? <Navigate to="/lobby" replace /> : <Login {...props}/>}
        />
        <Route 
          path="/register"
          element={isLogedIn ? <Navigate to="/lobby" replace /> : <Register {...props}/>}
        />

        {/* Redirect to login if not logged in */}
        <Route
          path="/profil"
          element={isLogedIn ? <Profil {...props}/> : <Navigate to="/login" replace />}
        />
        <Route
          path="/create_defis"
          element={isLogedIn ? <CreateDefis {...props}/> : <Navigate to="/login" replace />}
        />

        {/* Public routes */}
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