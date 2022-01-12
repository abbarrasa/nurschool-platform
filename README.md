# Nurschool
Assistant to the nursing service for schools.

Nurschool is a nursing service assistant for schools.
It provides tools for the management of medical files of students and communication between nurses and students' tutors.
It also provides tools such as forums and blogs for the diffusion of the nursing function at school in order to generate a community around it.

Nurschool is a PHP project using Domain-Driven Design (DDD) and Command Query Responsibility Segregation (CQRS) principles keeping the code as simple as possible. It tries to be as decoupled as possible from any framework, although it is based on a Symfony 5 implementation. It also follows a hexagonal architecture that allows it to be easily scaled and even implemented in a microservices architecture.

Some components used are:

Nurschool Sendgrid. An email component integrating Sendgrid REST API for Nurschool project. See more in https://github.com/abbarrasa/nurschool-sendgrid-bundle.

