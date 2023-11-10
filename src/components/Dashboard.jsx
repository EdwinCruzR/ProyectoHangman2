import { NavLink } from 'react-router-dom'
import { useAuthContext } from '@/Hooks/useAuthContext'

const Dashboard = () => {
  const { logout, isAuth } = useAuthContext()

  const linkIsActive = (isActive) =>
  isActive ? 'header__item-link header__item-link--is-active' : 'header__item-link'
  return (
    <nav className='header'>
      <NavLink to="/salagame.html">
          <img
              alt=""
              src="https://cdn.autobild.es/sites/navi.axelspringer.es/public/media/image/2016/09/569465-whatsapp-que-tus-contactos-ponen-rana-perfil.jpg?tf=3840x"
              width="50"
              height="30"
              className="d-inline-block align-top"
            />{' '}
            Docente
            </NavLink>
      <ul className='header__nav-list'>
        {isAuth
          ? (
            <>
              <li className='header__nav-item'>
              <NavLink className={({ isActive }) => linkIsActive(isActive)} to="/salagame.html/Arena">Arena</NavLink>
              </li>
              <li className='header__nav-item'>
              <NavLink className={({ isActive }) => linkIsActive(isActive)} to="/salagame.html/Join">Join</NavLink>
              </li>
              <li className='header__list-item'>
                <button className='header__item-link' onClick={logout}>Logout</button>
              </li>
            </>
            )
          : (
            <>
              <li className='header__nav-item'>
                <NavLink className={({ isActive }) => linkIsActive(isActive)} to='/login'>Login</NavLink>
              </li>
              <li className='header__nav-item'>
                <NavLink className={({ isActive }) => linkIsActive(isActive)} to='/signup'>Signup</NavLink>
              </li>
            </>
            )}

      </ul>
    </nav>
  )
}

export default Dashboard