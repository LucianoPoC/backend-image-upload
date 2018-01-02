import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import ImageTile from './ImageTile';
import Pagination from 'react-js-pagination';
import { Row, Col } from 'react-flexbox-grid';
import { FormGroup, Form, Button, FormControl } from 'react-bootstrap';

export default class App extends Component {
    constructor(props) {
        super(props);

        this.state = {
            items: '',
            pageCount: 9,
            meta: '',
            uri: 'http://api.cct-image-upload.tk/v1/uploads/',
            current_page: 1
        };

        this.fetchData = this.fetchData.bind(this);
        this.incrementViews = this.incrementViews.bind(this);
        this.incrementDownloads = this.incrementDownloads.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);

    }

    componentDidMount() {
        this.fetchData();
    }

    fetchData(page = 1) {
        axios.get(this.state.uri + '?page=' + page)
            .then((response) => {
                this.setState({
                    items: response.data.data,
                    meta: response.data.meta
                });

                this.setState({
                    current_page: page
                });
            })
            .catch((error) => {
                console.log(error);
            });
    }

    incrementViews(id) {


        axios.put(this.state.uri + id + '/views/').then(() => {
            this.fetchData(this.state.current_page)
        });
    }

    incrementDownloads(id) {
        axios.put(this.state.uri + id + '/downloads/').then(() => {
            this.fetchData(this.state.current_page)
        });
    }

    render() {
        return (
            <div>
                <Row center="xs">
                    <Col xs={12} md={4}>
                        <div className="panel">
                            <div className="panel-heading"><h1>Image Gallery</h1></div>
                            <Row center="xs">
                                <Col xs={12}>
                                    <Form id="form-upload" onSubmit={this.handleSubmit} encType="multipart/form-data">
                                        <Row center="xs">
                                            <Col xs={12}>
                                                <FormGroup>
                                                    <FormControl id="title" type="text" placeholder="Title" />
                                                </FormGroup>
                                                <FormGroup>
                                                    <FormControl id="file" type="file" />
                                                </FormGroup>
                                                <br />
                                                <FormGroup>
                                                    <Button bsStyle="primary" bsSize="large" block onClick={this.handleSubmit}>Upload</Button>
                                                </FormGroup>
                                            </Col>
                                        </Row>
                                    </Form>
                                </Col>
                            </Row>
                        </div>
                    </Col>
                </Row>

                <hr />

                <div className="grid-items">

                    { this.renderItems() }

                </div>

                <hr />

                <Row center="xs">
                    <Col xs>
                        <Row>
                            <Col xs={12}>
                                <Pagination
                                    activePage={this.state.current_page}
                                    itemsCountPerPage={this.state.meta.per_page}
                                    totalItemsCount={this.state.meta.total}
                                    pageRangeDisplayed={5}
                                    onChange={this.fetchData}
                                />
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <hr />

                { this.renderExportButton() }


            </div>
        );
    }

    renderExportButton() {
        if (this.state.items instanceof Array === false) {
            return false
        }

        return (
            <Row center="xs">
                <Col xs={12}>
                    <a download href={this.state.uri + "export"} ><Button bsStyle="primary" bsSize="large">Export</Button></a>
                </Col>
            </Row>
        )
    }

    renderItems() {
        if (this.state.items instanceof Array === false) {
            return <h1>There is no images to show.</h1>
        }

        return this.state.items.map((object, i) => {
            return (
                <ImageTile
                    key={i}
                    id={object.id}
                    title={object.title}
                    link={object.link}
                    downloads={object.downloads}
                    views={object.views}
                    onViewHandler={this.incrementViews}
                    onDownloadHandler={this.incrementDownloads}
                />
            )
        })
    }

    handleSubmit(e) {
        e.preventDefault();

        let file = document.getElementById('file').files[0];

        if (file === undefined) {
            return alert('The file field is required');
        }

        let data = new FormData();
        data.append('title', document.getElementById('title').value);
        data.append('file', file);

        const config = {
            headers: { 'content-type': 'multipart/form-data' }
        };

        axios.post(this.state.uri, data, config)
            .then((response) => {
                this.fetchData();

                alert('Image successfully uploaded');
            })
            .catch((err) => {
                switch (err.response.status) {
                    case 422:
                        alert(err.response.data.message.errors.file.reduce(
                            (preval, element) => preval + '\n' + element, '')
                        );
                        break;
                    case 413:
                        alert('Request Entity Too Large');
                        break;
                    case 500:
                        alert('Internal server error');
                        break;
                    default:
                        alert('Error');
                        break;
                }
            })
            .then(() => document.getElementById('form-upload').reset());
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
