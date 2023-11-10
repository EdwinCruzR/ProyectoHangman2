import { Routes, Route } from 'react-router-dom'
import Arena from '../pages/Arena'
import Join from '../pages/Join'

const RoutesIndex = () => {
  return (
    <Routes>
      <Route path='/salagame.html' element={<p>Aqui va el home</p>} />
      {/* <Route
        path='/dashboard'
        element={
        isAuth ? <Dashboard /> : <Login />
}
      />
      <Route path='/login' element={<Login />} />
      <Route
        path='/secret'
        element={
        isAuth ? <Secret /> : <Login />
}
      /> */}
      <Route path='/salagame.html/Arena' element={<Arena />} />
      <Route path='/salagame.html/Join' element={<Join />} />
    </Routes>
  )
}
export default RoutesIndex
