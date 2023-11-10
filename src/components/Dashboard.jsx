import { NavLink } from 'react-router-dom'
import {Container,Nav, Navbar } from 'react-bootstrap';

const Dashboard = () => {
  return (
    <Navbar bg="dark" data-bs-theme="dark">
        <Container>
        <Nav.Link ><NavLink to="/salagame.html">
          <img
              alt=""
              src="https://cdn.autobild.es/sites/navi.axelspringer.es/public/media/image/2016/09/569465-whatsapp-que-tus-contactos-ponen-rana-perfil.jpg?tf=3840x"
              width="50"
              height="30"
              className="d-inline-block align-top"
            />{' '}
            Docente
            </NavLink></Nav.Link>
          <Nav className="me-auto">
          <Nav.Link ><NavLink to="/salagame.html/Arena">Arena</NavLink></Nav.Link>
          <Nav.Link ><NavLink to="/salagame.html/Join">Join</NavLink></Nav.Link>
          <Nav.Link href="../../index.html">Salir</Nav.Link>
          </Nav>
        </Container>
      </Navbar>
  )
}

export default Dashboard