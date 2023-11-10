import { Routes, Route } from 'react-router-dom'
import Arena from '../pages/Arena'
import Join from '../pages/Join'
import Login from '../pages/Login'
import { useAuthContext } from '@/Hooks/useAuthContext'

const RoutesIndex = () => {
  const { isAuth } = useAuthContext()
  return (
    <Routes>
      <Route path='/salagame.html' element={<p>Aqui va el home</p>} />
      <Route
        path='/salagame.html/Arena'
        element={
        isAuth ? <Arena /> : <Login />
}
      />
      <Route path='/salagame.html/login' element={<Login />} />
      <Route
        path='/salagame.html/Join'
        element={
        isAuth ? <Join /> : <Login />
}
      />
    </Routes>
  )
}
export default RoutesIndex
