
import { BrowserRouter } from 'react-router-dom'
import Dashboard from './components/Dashboard'
import RoutesIndex from './routes/RoutesIndex'
import "./index.css"

function App () {
  return (
    <>
        <BrowserRouter>
          <Dashboard />
          <RoutesIndex />
        </BrowserRouter>
    </>
  )
}

export default App
